<?php

namespace App\Http\Controllers\Backend;

use App\Enums\ReferralType;
use App\Http\Controllers\Controller;
use App\Models\Referral;
use App\Models\ReferralLink;
use App\Models\ReferralRelationship;
use App\Models\ReferralTarget;
use App\Models\User;
use DataTables;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;
use App\Services\UserIbNetworkService;

class ReferralController extends Controller
{
    protected UserIbNetworkService $userIbNetworkService;

    /**
     * Display a listing of the resource.
     *
     * @return void
     */
    public function __construct(UserIbNetworkService $userIbNetworkService)
    {
        $this->userIbNetworkService = $userIbNetworkService;
        $this->middleware('permission:target-manage', ['only' => ['target', 'targetStore', 'targetUpdate']]);
        $this->middleware('permission:referral-list|referral-create|referral-edit|referral-delete', ['only' => ['index', 'store']]);
        $this->middleware('permission:referral-create', ['only' => ['store']]);
        $this->middleware('permission:referral-edit', ['only' => ['update']]);
        $this->middleware('permission:referral-delete', ['only' => ['delete']]);
    }

    /**
     * @return Application|Factory|View
     */
    public function index()
    {
        $targets = ReferralTarget::all();
        $investments = Referral::type(ReferralType::Investment);
        $deposits = Referral::type(ReferralType::Deposit);

        return view('backend.referral.index', compact('targets', 'investments', 'deposits'));
    }
    /**
     * @return RedirectResponse
     */
    public function store(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'type' => 'required',
            'referral_target_id' => Rule::unique('referrals')->where(fn ($query) => $query->where('type', $request->type)),
            'target_amount' => 'required',
            'bounty' => 'required',
        ]);

        if ($validator->fails()) {
            notify()->error($validator->errors()->first(), 'Error');

            return redirect()->back();
        }

        $input = $request->all();

        Referral::create([
            'type' => $input['type'],
            'referral_target_id' => $input['referral_target_id'],
            'bounty' => $input['bounty'],
            'target_amount' => $input['target_amount'],
            'description' => $input['description'],
        ]);

        notify()->success('Referral created successfully');

        return redirect()->route('admin.referral.index');
    }

    public function addDirectReferral(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'ref_id' => 'required',
            'user_id' => 'required',
        ]);

        if ($validator->fails()) {
            notify()->error($validator->errors()->first(), 'Error');

            return redirect()->back();
        }

        $input = $request->all();

        // Validate that ref_id and user_id are different
        if ($input['ref_id'] == $input['user_id']) {
            notify()->error('A user cannot be their own referral parent', 'Error');
            return redirect()->back();
        }

        // Check for circular reference - prevent adding a user's ancestor as their child
        $childUser = User::find($input['user_id']);
        if (!$childUser) {
            notify()->error('Child user not found', 'Error');
            return redirect()->back();
        }

        // Check if the child user (user_id) exists in the parent chain of current user (ref_id)
        // This prevents: if ref_id has ancestry including user_id, then adding user_id as child creates loop
        if ($this->isUserInAncestry($input['user_id'], $input['ref_id'])) {
            $parentUser = User::find($input['ref_id']);
            $childUserEmail = $childUser->email ?? 'Unknown';
            $parentUserEmail = $parentUser->email ?? 'Unknown';
            
            notify()->error('Cannot add referral: This would create a circular reference. The user you are trying to add (' . $childUserEmail . ') is already in the parent chain of the current user (' . $parentUserEmail . ').', 'Error');
            return redirect()->back();
        }

        $pUser = User::find($input['ref_id']);
        $pUser->getReferrals();
        $referral = ReferralLink::where('user_id', $input['ref_id'])->first();
        
        if (!is_null($referral)) {
            try {
                // Begin transaction for data consistency
                DB::beginTransaction();

                $childUser = User::find($input['user_id']);
                
                // Remove existing referrals & IB relationships
                ReferralRelationship::where('user_id', $input['user_id'])->delete();
                remove_child_agent($childUser);
                
                // Remove existing is_part_of_master_ib meta from the child user and their network
                $this->userIbNetworkService->removeMetaFromNetwork($childUser, 'is_part_of_master_ib');

                // Add new referrals & IB relationships
                ReferralRelationship::create(['referral_link_id' => $referral->id, 'user_id' => $input['user_id']]);
                User::find($input['user_id'])->update([
                    'ref_id' => $referral->user->id,
                ]);
                add_child_agent($pUser);

				// Determine nearest approved IB group from parent upward and propagate to child's network
				$ibGroupIdToApply = null;
				if ($pUser && $pUser->ib_status === 'approved' && !is_null($pUser->ib_group_id)) {
					$ibGroupIdToApply = (int) $pUser->ib_group_id;
				} else {
					$ibGroupIdToApply = $this->findNearestApprovedIbGroupId($pUser);
				}

				if ($ibGroupIdToApply) {
					$this->userIbNetworkService->syncMeta($childUser, 'is_part_of_master_ib', $ibGroupIdToApply);
				}

				// Propagate parent's branch to child user and entire downline (no IB checks)
				// $parentBranchId = getUserBranchId($pUser->id, $pUser);
				// $downlineUserIds = $this->collectFullDownlineUserIds($childUser);
				// if ($parentBranchId) {
				// 	foreach ($downlineUserIds as $uid) {
				// 		setUserBranchId($uid, (int) $parentBranchId);
				// 	}
				// } else {
				// 	foreach ($downlineUserIds as $uid) {
				// 		setUserBranchId($uid, null);
				// 	}
				// }

				// // Propagate parent's staff to child user and entire downline (no IB checks)
				// $parentStaffIds = $pUser->staff()->pluck('admins.id')->toArray();
				// if (!empty($parentStaffIds)) {
				// 	foreach ($downlineUserIds as $uid) {
				// 		DB::table('staff_user')->where('user_id', $uid)->delete();
				// 		$rows = [];
				// 		$now = now();
				// 		foreach ($parentStaffIds as $sid) {
				// 			$rows[] = [
				// 				'staff_id' => (int) $sid,
				// 				'user_id' => (int) $uid,
				// 				'created_at' => $now,
				// 				'updated_at' => $now,
				// 			];
				// 		}
				// 		if (!empty($rows)) {
				// 			DB::table('staff_user')->insert($rows);
				// 		}
				// 	}
				// } else {
				// 	// No staff on parent: remove staff for child and their network
				// 	DB::table('staff_user')->whereIn('user_id', $downlineUserIds)->delete();
				// }

				DB::commit();
				notify()->success('Referral created successfully');

                return redirect()->back();
                
            } catch (\Exception $e) {
                DB::rollBack();
                Log::error('Error adding direct referral: ' . $e->getMessage());
                notify()->error('Error creating referral: ' . $e->getMessage());
                return redirect()->back();
            }
        } else {
            notify()->error('Did not find referral link of parent user. Please try again');
            return redirect()->back();
        }
    }

	/**
	 * Collect all downline user IDs including the given root user, without IB-related skips.
	 */
	// protected function collectFullDownlineUserIds(User $root): array
	// {
	// 	$collectedIds = [$root->id];
	// 	$queue = [$root->id];

	// 	while (!empty($queue)) {
	// 		$children = User::whereIn('ref_id', $queue)->pluck('id')->toArray();
	// 		$children = array_values(array_diff($children, $collectedIds));
	// 		if (empty($children)) {
	// 			break;
	// 		}
	// 		$collectedIds = array_merge($collectedIds, $children);
	// 		$queue = $children;
	// 	}

	// 	return $collectedIds;
	// }

	/**
	 * Find nearest ancestor of the given user who is an approved IB and return their ib_group_id.
	 */
	protected function findNearestApprovedIbGroupId(?User $startFrom): ?int
	{
		$current = $startFrom;
		while ($current && $current->ref_id) {
			$parent = User::find($current->ref_id);
			if (!$parent) {
				break;
			}
			if ($parent->ib_status === 'approved' && !is_null($parent->ib_group_id)) {
				return (int) $parent->ib_group_id;
			}
			$current = $parent;
		}
		return null;
	}


	/**
	 * Check if a user is in the ancestry (upline/parent chain) of another user.
	 * 
	 * This traverses upward through the parent chain to detect circular references.
	 * 
	 * Example: If checking isUserInAncestry(9, 1)
	 * - Start from user 1
	 * - Traverse up: 1 → 2 → 3 → ... 
	 * - If we find user 9 in this chain, return true
	 * - This prevents adding user 9 as a child of user 1 (would create loop)
	 * 
	 * @param int $ancestorId The ID to search for in the ancestry (the user being added as child)
	 * @param int $descendantId The user whose ancestry we're checking (current user/parent)
	 * @return bool True if ancestorId is found in descendantId's upline chain
	 */
	protected function isUserInAncestry(int $ancestorId, int $descendantId): bool
	{
		$current = User::find($descendantId);
		$visited = []; // Track visited IDs to prevent infinite loops
		$maxDepth = 1000; // Safety limit
		$depth = 0;

		while ($current && $current->ref_id && $depth < $maxDepth) {
			// Prevent infinite loops in case of existing circular references
			if (in_array($current->ref_id, $visited)) {
				Log::warning("Circular reference detected in existing ancestry data at user ID: {$current->ref_id}");
				break;
			}

			// If we find the ancestor in the upline chain
			if ($current->ref_id == $ancestorId) {
				return true;
			}

			$visited[] = $current->ref_id;
			$current = User::find($current->ref_id);
			$depth++;
		}

		if ($depth >= $maxDepth) {
			Log::error("Maximum depth reached while checking ancestry. Possible existing circular reference.");
		}

		return false;
	}

    /**
     * @return RedirectResponse
     */
    public function delete(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required',
        ]);

        if ($validator->fails()) {
            notify()->error($validator->errors()->first(), 'Error');

            return redirect()->back();
        }

        $referral = Referral::find($request->id);

        if (null != $referral) {
            $referral->delete();
        }
        notify()->success('Referral Delete successfully');

        return redirect()->route('admin.referral.index');

    }

    public function deleteDirectReferral(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required',
        ]);

        if ($validator->fails()) {
            notify()->error($validator->errors()->first(), 'Error');

            return redirect()->back();
        }

        $referral = User::find($request->id);

        if (null != $referral) {
            try {
                // Begin transaction for data consistency
                DB::beginTransaction();

                // Remove referral relationship
                $referral->ref_id = null;
                $referral->save();
                
                // Delete referral relationship record
                ReferralRelationship::where('user_id', $request->id)->delete();
                
                // Remove child agent (existing functionality)
                remove_child_agent($referral);
                
                // Remove is_part_of_master_ib meta from the user and their network
                $removedCount = $this->userIbNetworkService->removeMetaFromNetwork($referral, 'is_part_of_master_ib');
                
                DB::commit();
                
                notify()->success('Referral deleted successfully');
                
            } catch (\Exception $e) {
                DB::rollBack();
                Log::error('Error deleting direct referral: ' . $e->getMessage());
                notify()->error('Error deleting referral: ' . $e->getMessage());
            }
        } else {
            notify()->error('User not found');
        }

        return redirect()->back();
    }

    /**
     * @return Application|Factory|View
     */
    public function target()
    {
        $targets = ReferralTarget::all();

        return view('backend.referral.target', compact('targets'));
    }

    /**
     * @return RedirectResponse
     */
    public function targetStore(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'name' => 'required',
        ]);

        if ($validator->fails()) {
            notify()->error($validator->errors()->first(), 'Error');

            return redirect()->back();
        }

        ReferralTarget::create(['name' => $request->name]);

        notify()->success('Target created successfully');

        return redirect()->route('admin.referral.target');
    }

    /**
     * @return RedirectResponse
     */
    public function targetUpdate(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required',
            'name' => 'required',
        ]);

        if ($validator->fails()) {
            notify()->error($validator->errors()->first(), 'Error');

            return redirect()->back();
        }
        $input = $request->all();
        ReferralTarget::find($input['id'])->update(['name' => $input['name']]);

        notify()->success('Target Update successfully');

        return redirect()->route('admin.referral.target');
    }

    /**
     * @return RedirectResponse
     */
    public function update(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'id' => 'required',
            'type' => 'required',
            'referral_target_id' => Rule::unique('referrals')->where(fn ($query) => $query->where('type', $request->type))->ignore($request->id),
            'target_amount' => 'required|regex:/^\d+(\.\d{1,2})?$/',
            'bounty' => 'required|regex:/^\d+(\.\d{1,2})?$/',
        ]);

        if ($validator->fails()) {
            notify()->error($validator->errors()->first(), 'Error');

            return redirect()->back();
        }

        $input = $request->all();

        Referral::find($input['id'])->update([
            'referral_target_id' => $input['referral_target_id'],
            'target_amount' => $input['target_amount'],
            'bounty' => $input['bounty'],
            'description' => $input['description'],
        ]);

        notify()->success('Referral Updated successfully');

        return redirect()->route('admin.referral.index');
    }

    public function directList($id, Request $request)
    {

        if ($request->ajax()) {
            $data = User::where('users.ref_id', $id)
                ->with('realTradingAccounts') // Eager load to prevent N+1
                ->select('users.*');

            return Datatables::of($data)
                ->addIndexColumn()
                ->editColumn('avatar', 'backend.user.include.__avatar')
                ->editColumn('kyc', 'backend.user.include.__kyc')
                ->editColumn('status', 'backend.user.include.__status')
                ->addColumn('real_forex_accounts', function ($user) {
                    $accounts = $user->realTradingAccounts;
                    if ($accounts->isEmpty()) {
                        return '<span class="text-muted">-</span>';
                    }
                    return $accounts->map(function ($account) {
                        try {
                            $equity = get_mt5_account_balance($account->login);
                        } catch (\Throwable $e) {
                            $equity = 'Error';
                        }

                        return "Login: {$account->login}, Balance: " . (is_numeric($equity) ? number_format($equity, 2) : $equity);
                    })->implode('<br>');
                })
                ->editColumn('balance', 'backend/user/include/__total_balance_mt5')
                ->addColumn('action', 'backend.user.include.__direct_referral_action')
                ->orderColumn('avatar', function ($query, $direction) {
                    $query->orderBy('users.first_name', $direction)
                          ->orderBy('users.last_name', $direction);
                })
                ->rawColumns(['avatar', 'kyc','real_forex_accounts','balance', 'status', 'action'])
                ->make(true);
        }
    }
}
