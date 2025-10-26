<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\Branch;
use App\Models\Department;
use App\Models\Designation;
use App\Models\EmailTemplate;
use App\Models\User;
use App\Models\IbGroup;
use App\Models\ForexSchema;
use App\Models\ForexAccount;
use App\Traits\ImageUpload;
use App\Traits\NotifyTrait;
use Arr;
use DB;
use Hash;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use PragmaRX\Google2FALaravel\Support\Authenticator;
use Spatie\Permission\Models\Role;

class StaffController extends Controller
{
    use ImageUpload, NotifyTrait;

    /**
     * Display a listing of the resource.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('permission:staff-list|staff-create|staff-edit', ['only' => ['index', 'store']]);
        $this->middleware('permission:staff-create', ['only' => ['store']]);
        $this->middleware('permission:staff-edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:staff-delete', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return Application|Factory|View
     */
    public function index(Request $request)
    {
        $staff = Auth::user();
        $staffs = Admin::all();
        $superAdmin = Admin::find(1);
        $roles = Role::all();
        $departments = Department::with('children')->whereNull('parent_id')->get();
        $designations = Designation::with('children')->whereNull('parent_id')->get();
        $branches = Branch::where('status', 1)->get();

        // Count active and inactive staff
        $activeStaffCount = Admin::where('status', true)->count();
        $inactiveStaffCount = Admin::where('status', false)->count();
        $users = User::all(); // Fetch all users
        $attachedUsers = $staff->users; // Fetch attached users
        if ($request->ajax()) {
            $status = $request->status; // active or inactive
            if ($status == 'active') {
                $staffs = Admin::where('status', true)->get();
            } elseif ($status == 'inactive') {
                $staffs = Admin::where('status', false)->get();
            }

            return response()->json([
                'staffs' => view('backend.staff.include.__staff_list', ['staff' => $staffs])->render(),
            ]);
        }

        return view('backend.staff.index', compact('staff', 'staffs', 'activeStaffCount', 'inactiveStaffCount', 'superAdmin', 'roles', 'departments', 'designations', 'users', 'attachedUsers', 'branches'));

    }

    public function create(Request $request)
    {
        if ($request->ajax()) {
            $roles = Role::all();
            $departments = Department::with('children')->whereNull('parent_id')->get();
            $designations = Designation::with('children')->whereNull('parent_id')->get();
            $branches = Branch::where('status', 1)->get();

            $view = view('backend.staff.create', compact('roles', 'departments', 'designations', 'branches'))->render();

            // Send back the view HTML as a response
            return response()->json(['html' => $view]);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return RedirectResponse
     */
    public function store(Request $request)
    {
        // Validate the input
        $validator = Validator::make($request->all(), [
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:admins,email',
            'password' => 'required|string|min:8|same:confirm-password',
            'confirm-password' => 'required_with:password|string|min:8',
            'role' => 'required',
            'status' => 'boolean',
            'employee_id' => 'nullable|integer|min:1',
            'department_id' => 'nullable|exists:departments,id',
            'designation_id' => 'nullable|exists:designations,id',
            'date_of_joining' => 'nullable|date',
            'date_of_birth' => 'nullable|date',
            'gender' => 'nullable|in:male,female,other',
            'marital_status' => 'nullable|in:single,married',
            'employment_type' => 'nullable|in:permanent,on contract,temporary,trainee',
            'employment_status' => 'nullable|in:active,terminated,deceased,resigned,probation,notice period',
            'source_of_hire' => 'nullable|in:direct,referral,web,newspaper',
            'location' => 'nullable|string|max:255',
            'work_phone' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:255',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'key' => 'nullable|string|max:255',
            'branch_id' => 'nullable|exists:branches,id',
        ]);

        if ($validator->fails()) {
            notify()->error($validator->errors()->first(), 'Error');
            return redirect()->back();
        }

        // Check for Super-Admin role assignment
        if (in_array('Super-Admin', (array)$request->input('role'))) {
            if (!Auth::guard('admin')->user()->hasRole('Super-Admin')) {
                notify()->error('Only Super-Admin can assign Super-Admin role.', 'Unauthorized');
                return redirect()->back();
            }
        }

        try {
            DB::beginTransaction();

            $input = $request->all();
            $input['password'] = Hash::make($input['password']);
            $input['employee_id'] = $input['employee_id'] ?: null;
            $input['department_id'] = $input['department_id'] ?: null;
            $input['designation_id'] = $input['designation_id'] ?: null;
            $input['date_of_joining'] = $input['date_of_joining'] ?: null;
            $input['date_of_birth'] = $input['date_of_birth'] ?: null;
            $input['work_phone'] = $input['work_phone'] ?: null;
            $input['phone'] = $input['phone'] ?: null;

            if ($request->hasFile('avatar')) {
                $logo = self::imageUploadTrait($request->file('avatar'));
                $input['avatar'] = $logo;
            }

            $admin = Admin::create($input);
            $admin->assignRole($request->input('role'));

            // Handle branch assignment (only for non-Super-Admin roles)
            if ($request->input('role') !== 'Super-Admin' && $request->filled('branch_id')) {
                $branchId = $request->input('branch_id');
                $admin->branches()->sync([$branchId]);
            }

            DB::commit();

            // Notify configured staff_site_email recipients about new staff creation
            try {
                $rawStaffEmails = (string) setting('staff_site_email', 'global');
                if (!empty($rawStaffEmails)) {
                    $creator = Auth::guard('admin')->user();
                    $creatorFullName = trim(($creator->first_name ?? '') . ' ' . ($creator->last_name ?? '')) ?: ($creator->name ?? '');
                    $shortcodes = [
                        '[[full_name]]' => trim(($input['first_name'] ?? '') . ' ' . ($input['last_name'] ?? '')) ?: ($input['name'] ?? ''),
                        '[[email]]' => $input['email'] ?? '',
                        '[[created_by_name]]' => $creatorFullName,
                        '[[created_by_email]]' => $creator->email ?? '',
                        '[[site_title]]' => setting('site_title', 'global'),
                        '[[site_url]]' => url('/'),
                    ];

                    $emails = collect(preg_split('/[;,]/', $rawStaffEmails))
                        ->map(function ($e) { return trim($e); })
                        ->filter(function ($e) { return !empty($e); })
                        ->unique()
                        ->values();

                    foreach ($emails as $email) {
                        $this->mailNotify($email, 'admin_new_staff_created', $shortcodes);
                    }
                }
            } catch (\Exception $e) {
                \Log::warning('Failed to send staff creation emails: ' . $e->getMessage());
            }

            notify()->success('Staff created successfully');
            return redirect()->route('admin.staff.index');
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Staff creation failed: ' . $e->getMessage());
            notify()->error('An error occurred while creating the staff.', 'Error');
            return redirect()->back();
        }
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return string
     */
    public function edit($id)
    {
        $staff = Admin::with('branches')->find($id);
        $roles = Role::all();
        $departments = Department::with('children')->whereNull('parent_id')->get();
        $designations = Designation::with('children')->whereNull('parent_id')->get();
        $branches = Branch::where('status', 1)->get();

        return view('backend.staff.edit', compact('staff', 'roles', 'departments', 'designations', 'branches'))->render();
    }


    /**
     * Update the specified resource in storage.
     *
     * @param int $id
     * @return RedirectResponse
     */
    public function update(Request $request, $id)
    {
        $staff = Admin::find($id);

        if (!$staff) {
            return response()->json(['success' => false, 'message' => 'Staff not found.'], 404);
        }

        try {
            $validator = Validator::make($request->all(), [
                'first_name' => 'required|string|max:255',
                'last_name' => 'required|string|max:255',
                'name' => 'required|string|max:255',
                'email' => 'required|email|max:255|unique:admins,email,' . $id,
                'password' => 'nullable|string|min:8|same:confirm-password',
                'confirm-password' => 'required_with:password|string|min:8',
                'role' => 'nullable',
                'status' => 'boolean',
                'employee_id' => 'nullable|integer|min:1',
                'department_id' => 'nullable|exists:departments,id',
                'designation_id' => 'nullable|exists:designations,id',
                'date_of_joining' => 'nullable|date',
                'date_of_birth' => 'nullable|date',
                'gender' => 'nullable|in:male,female,other',
                'marital_status' => 'nullable|in:single,married',
                'employment_type' => 'nullable|in:permanent,on contract,temporary,trainee',
                'employment_status' => 'nullable|in:active,terminated,deceased,resigned,probation,notice period',
                'source_of_hire' => 'nullable|in:direct,referral,web,newspaper',
                'location' => 'nullable|string|max:255',
                'work_phone' => 'nullable|string|max:255',
                'phone' => 'nullable|string|max:255',
                'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
                'key' => 'nullable|string|max:255',
                'branch_id' => 'nullable|exists:branches,id',
            ]);

            if ($validator->fails()) {
                return response()->json(['success' => false, 'message' => $validator->errors()->first()], 422);
            }

            // Validate role change for Super-Admin
            $currentUser = Auth::guard('admin')->user();
            if ($request->filled('role')) {
                $newRoles = (array)$request->input('role');

                if (in_array('Super-Admin', $newRoles) || $staff->hasRole('Super-Admin')) {
                    if (!$currentUser->hasRole('Super-Admin')) {
                        return response()->json([
                            'success' => false,
                            'message' => 'Only a Super-Admin can assign or modify the Super-Admin role.',
                        ], 403);
                    }
                }
            }

            DB::beginTransaction();

            $input = $request->all();
            $input['employee_id'] = $request->input('employee_id') ?: null;
            $input['department_id'] = $request->input('department_id') ?: null;
            $input['designation_id'] = $request->input('designation_id') ?: null;
            $input['date_of_joining'] = $request->input('date_of_joining') ?: null;
            $input['date_of_birth'] = $request->input('date_of_birth') ?: null;
            unset($input['department'], $input['designation']);

            if (!empty($input['password'])) {
                $input['password'] = Hash::make($input['password']);
            } else {
                $input = Arr::except($input, ['password']);
            }

            if ($request->hasFile('avatar')) {
                $logo = self::imageUploadTrait($request->file('avatar'), $staff->avatar);
                $input['avatar'] = $logo;
            }

            $staff->update($input);

            if ($request->filled('role')) {
                DB::table('model_has_roles')->where('model_id', $id)->delete();
                $staff->assignRole($request->input('role'));
            }

            // Handle branch assignment (only for non-Super-Admin roles)
            $currentRole = $staff->roles->first()->name ?? $request->input('role');
            if ($currentRole !== 'Super-Admin') {
                if ($request->filled('branch_id')) {
                    $branchId = $request->input('branch_id');
                    $staff->branches()->sync([$branchId]);
                } else {
                    // If no branch is selected, remove all branch assignments
                    $staff->branches()->sync([]);
                }
            }

            DB::commit();

            $roles = Role::all();
            $departments = Department::with('children')->whereNull('parent_id')->get();
            $designations = Designation::with('children')->whereNull('parent_id')->get();
            $branches = Branch::where('status', 1)->get();

            // Refresh the staff with branches relationship
            $staff = $staff->fresh(['branches']);
            $updatedStaff = view('backend.staff.edit', compact('staff', 'roles', 'departments', 'designations', 'branches'))->render();
            notify()->success('Staff updated successfully!');

            return response()->json([
                'success' => true,
                'message' => 'Staff updated successfully!',
                'updatedHtml' => $updatedStaff
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => 'An error occurred: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Recursive function to fetch referral network.
     */

    protected function invalidateUserSession($user)
    {
        // Path to the session files
        $sessionFilesPath = storage_path('framework/sessions');

        // Get all session files
        $sessionFiles = File::files($sessionFilesPath);

        // Iterate over session files and delete those belonging to the user
        foreach ($sessionFiles as $file) {
            $content = File::get($file);

            // Check if the session file contains the user's ID
            if (str_contains($content, 'login_web_' . $user->id)) {
                File::delete($file);
            }
        }
    }


    public function security($id)
    {

        $user = Admin::find($id);
        if (null == $user->google2fa_secret) {
            $google2fa = app('pragmarx.google2fa');
            $secret = $google2fa->generateSecretKey();
            //dd($user,$google2fa,$secret);
            $user->update([
                'google2fa_secret' => $secret,
            ]);
        }

        return view('backend.staff.security.index', compact('user'));
    }

    public function twoFaPin()
    {
        return view('backend.auth.two_fa_pin');
    }

    public function twoFa()
    {

        notify()->success(__('QR Code And Secret Key Generate successfully'));

        return redirect()->back();

    }

    public function actionTwoFa(Request $request)
    {
        $user = \Auth::user();

        $google2fa = app('pragmarx.google2fa');

        if ($request->status == 'disable') {
            // Allow disabling via either Google Authenticator code OR account password
            $inputCode = (string) $request->input('one_time_password');
            $isGaVerified = $user->google2fa_secret
                ? $google2fa->verifyKey($user->google2fa_secret, $inputCode, 0)
                : false;
            $isPasswordVerified = Hash::check($inputCode, $user->password);

            if ($isGaVerified || $isPasswordVerified) {
                $user->update([
                    'two_fa' => 0,
                ]);
                notify()->success(__('2Fa Authentication Disable successfully'));
                return redirect()->back();
            }

            notify()->warning(__('Wrong verification. Please enter valid GA code or your password'));
            return redirect()->back();

        } elseif ($request->status == 'enable') {
            $inputCode = (string) $request->input('one_time_password');
            $isGaVerified = $user->google2fa_secret
                ? $google2fa->verifyKey($user->google2fa_secret, $inputCode, 0)
                : false;

            if ($isGaVerified) {
                $user->update([
                    'two_fa' => 1,
                ]);
                notify()->success(__('2Fa Authentication Enable successfully'));
                return redirect()->back();
            }

            notify()->warning(__('2Fa Authentication Wrong One Time Key'));
            return redirect()->back();
        }
    }


    public function destroy($id)
    {
        DB::beginTransaction();

        try {
            $staff = Admin::find($id);
            if ($staff->getRoleNames()->first() === 'Super-Admin') {
                notify()->warning('Super admin not deleteble');
                return redirect()->back();
            }
            if ($staff->users()->exists()) {
                $staff->users()->detach();
            }

            $staff->delete();

            DB::commit();

            notify()->success('staff deleted successfully');
            return redirect()->back();
        } catch (\Exception $e) {
            DB::rollback();

            \Log::error('Error deleting staff: ' . $e->getMessage());

            notify()->error('An error occurred while deleting the staff. Please try again.', 'Error');
            return redirect()->back();
        }
    }

    public function staffLogin($id)
    {
        if (Auth::guard('admin')->check() && Auth::guard('admin')->user()->getRoleNames()->contains('Super-Admin')) {

            session(['super_admin_id' => Auth::guard('admin')->user()->id]);

            Auth::guard('admin')->loginUsingId($id);

            session(['impersonated_id' => $id]);

            notify()->success('Logged in as staff successfully');
            return redirect()->route('admin.dashboard');
        }

        notify()->error('Unauthorized action.');
        return redirect()->back();
    }

    public function stopImpersonation()
    {
        if (Auth::guard('admin')->check() && session('impersonated_id')) {

            $superAdminId = session('super_admin_id');

            Auth::guard('admin')->logout();

            Auth::guard('admin')->loginUsingId($superAdminId);

            session()->forget('impersonated_id');
            session()->forget('super_admin_id');

            return redirect()->route('admin.dashboard');
        }

        notify()->error('Unauthorized action.');
        return redirect()->back();
    }


}
