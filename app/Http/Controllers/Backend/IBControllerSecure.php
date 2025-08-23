<?php

namespace App\Http\Controllers\Backend;

use App\Enums\IBStatus;
use App\Http\Controllers\Controller;
use App\Models\IbQuestion;
use App\Models\User;
use App\Services\AuditLogService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class IBControllerSecure extends Controller
{
    protected $auditLogService;

    public function __construct(AuditLogService $auditLogService)
    {
        $this->auditLogService = $auditLogService;
    }

    /**
     * Secure IB approval with proper transaction handling
     */
    public function approveIbMemberSecure(Request $request)
    {
        DB::beginTransaction();
        
        try {
            // Enhanced validation
            $validator = Validator::make($request->all(), [
                'user_id' => 'required|integer|exists:users,id',
                'ib_group_id' => 'required|integer|exists:ib_groups,id',
            ]);

            if ($validator->fails()) {
                DB::rollback();
                Log::warning('IB Approval validation failed', [
                    'user_id' => $request->user_id,
                    'errors' => $validator->errors(),
                    'admin_id' => auth()->id()
                ]);
                
                return response()->json([
                    'error' => 'Validation failed: ' . $validator->errors()->first(),
                    'reload' => false
                ], 422);
            }

            $user = User::findOrFail($request->user_id);
            
            // Check if user is already approved
            if ($user->ib_status === IBStatus::APPROVED) {
                DB::rollback();
                return response()->json([
                    'error' => 'User is already an approved IB member',
                    'reload' => false
                ], 400);
            }

            // Store original status for audit
            $originalStatus = $user->ib_status;
            $originalGroupId = $user->ib_group_id;

            // Update user status
            $user->ib_status = IBStatus::APPROVED;
            $user->ib_group_id = $request->ib_group_id;
            $user->approved_at = now();
            $user->approved_by = auth()->id();
            $user->save();

            // Manage rebate rules
            $this->manageUserRebateRulesSecure($user, $request->ib_group_id);

            // Sync user metadata
            $this->userIbNetworkService->syncMeta(
                $user,
                'is_part_of_master_ib',
                $user->ib_group_id
            );

            // Create audit log
            $this->auditLogService->log([
                'action' => 'ib_member_approved',
                'model' => 'User',
                'model_id' => $user->id,
                'admin_id' => auth()->id(),
                'changes' => [
                    'ib_status' => ['from' => $originalStatus, 'to' => IBStatus::APPROVED],
                    'ib_group_id' => ['from' => $originalGroupId, 'to' => $request->ib_group_id]
                ],
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent()
            ]);

            // Send notifications
            $this->sendApprovalNotifications($user);

            DB::commit();

            Log::info('IB member approved successfully', [
                'user_id' => $user->id,
                'admin_id' => auth()->id(),
                'ib_group_id' => $request->ib_group_id
            ]);

            return response()->json([
                'title' => 'Account Approved for IB',
                'success' => 'User has been successfully approved as IB Member',
                'reload' => true
            ]);

        } catch (\Exception $e) {
            DB::rollback();
            
            Log::error('IB approval failed', [
                'user_id' => $request->user_id,
                'admin_id' => auth()->id(),
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'error' => 'Failed to approve IB member. Please try again.',
                'reload' => false
            ], 500);
        }
    }

    /**
     * Secure form update with enhanced validation
     */
    public function updateSecure(Request $request, $id)
    {
        DB::beginTransaction();
        
        try {
            $validator = Validator::make($request->all(), [
                'name' => [
                    'required',
                    'string',
                    'max:255',
                    'unique:ib_questions,name,' . $id,
                    'regex:/^[a-zA-Z0-9\s\-\_]+$/' // Only alphanumeric, spaces, hyphens, underscores
                ],
                'status' => 'required|boolean',
                'fields' => 'required|array|min:1',
                'fields.*.name' => 'required|string|max:100',
                'fields.*.type' => 'required|in:text,checkbox,radio,dropdown',
                'fields.*.validation' => 'required|in:required,nullable',
                'fields.*.options' => 'required_if:fields.*.type,checkbox,radio,dropdown|array'
            ]);

            if ($validator->fails()) {
                DB::rollback();
                return response()->json([
                    'errors' => $validator->errors()
                ], 422);
            }

            $ibQuestion = IbQuestion::findOrFail($id);
            
            // Store original data for audit
            $originalData = $ibQuestion->toArray();

            // Sanitize and prepare data
            $sanitizedFields = $this->sanitizeFormFields($request->fields);
            
            $updateData = [
                'name' => strip_tags($request->name),
                'status' => $request->status,
                'fields' => json_encode($sanitizedFields),
                'updated_by' => auth()->id(),
                'updated_at' => now()
            ];

            $ibQuestion->update($updateData);

            // Create audit log
            $this->auditLogService->log([
                'action' => 'ib_form_updated',
                'model' => 'IbQuestion',
                'model_id' => $ibQuestion->id,
                'admin_id' => auth()->id(),
                'changes' => [
                    'original' => $originalData,
                    'updated' => $updateData
                ],
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent()
            ]);

            DB::commit();

            return response()->json([
                'success' => 'IB form updated successfully',
                'redirect' => route('admin.ib-form.index')
            ]);

        } catch (\Exception $e) {
            DB::rollback();
            
            Log::error('IB form update failed', [
                'form_id' => $id,
                'admin_id' => auth()->id(),
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'error' => 'Failed to update IB form. Please try again.'
            ], 500);
        }
    }

    /**
     * Sanitize form fields to prevent XSS and injection attacks
     */
    private function sanitizeFormFields(array $fields): array
    {
        $sanitized = [];
        
        foreach ($fields as $field) {
            $sanitized[] = [
                'name' => strip_tags($field['name']),
                'type' => $field['type'],
                'validation' => $field['validation'],
                'options' => isset($field['options']) ? 
                    array_map('strip_tags', $field['options']) : null
            ];
        }
        
        return $sanitized;
    }

    /**
     * Secure rebate rules management
     */
    private function manageUserRebateRulesSecure($user, $ibGroupId)
    {
        try {
            // Remove existing rules
            $user->userIbRules()->delete();

            if ($ibGroupId) {
                // Get rebate rules for the IB group
                $rebateRules = RebateRule::whereHas('ibGroups', function ($query) use ($ibGroupId) {
                    $query->where('ib_groups.id', $ibGroupId);
                })->get();

                // Create new rules
                foreach ($rebateRules as $rule) {
                    $user->userIbRules()->create([
                        'ib_group_id' => $ibGroupId,
                        'rebate_rule_id' => $rule->id,
                        'created_by' => auth()->id()
                    ]);
                }
            }
        } catch (\Exception $e) {
            Log::error('Failed to manage user rebate rules', [
                'user_id' => $user->id,
                'ib_group_id' => $ibGroupId,
                'error' => $e->getMessage()
            ]);
            throw $e;
        }
    }

    /**
     * Send approval notifications with error handling
     */
    private function sendApprovalNotifications($user)
    {
        try {
            $shortcodes = [
                '[[full_name]]' => $user->full_name,
                '[[email]]' => $user->email,
                '[[site_title]]' => setting('site_title', 'global'),
                '[[site_url]]' => route('home'),
                '[[status]]' => IBStatus::APPROVED,
            ];

            $this->mailNotify($user->email, 'ib_action', $shortcodes);
            $this->smsNotify('ib_action', $shortcodes, $user->phone);
            $this->pushNotify('ib_action', $shortcodes, route('user.referral'), $user->id);
            
        } catch (\Exception $e) {
            // Log notification failure but don't fail the approval
            Log::warning('IB approval notifications failed', [
                'user_id' => $user->id,
                'error' => $e->getMessage()
            ]);
        }
    }
}
