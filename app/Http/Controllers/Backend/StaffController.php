<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\Department;
use App\Models\Designation;
use App\Models\User;
use App\Models\IbGroup;
use App\Traits\ImageUpload;
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
    use ImageUpload;

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
        $roles = Role::whereNot('name', 'Super-Admin')->get();
        $departments = Department::with('children')->whereNull('parent_id')->get();
        $designations = Designation::with('children')->whereNull('parent_id')->get();

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

        return view('backend.staff.index', compact('staff', 'staffs', 'activeStaffCount', 'inactiveStaffCount', 'superAdmin', 'roles', 'departments', 'designations', 'users', 'attachedUsers'));

    }

    public function create(Request $request)
    {
        if ($request->ajax()) {
            $roles = Role::whereNot('name', 'Super-Admin')->get();
            $departments = Department::with('children')->whereNull('parent_id')->get();
            $designations = Designation::with('children')->whereNull('parent_id')->get();

            $view = view('backend.staff.create', compact('roles', 'departments', 'designations'))->render();

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
            'first_name' => 'required',
            'last_name' => 'required',
            'name' => 'required',
            'email' => 'required|email|unique:admins,email',
            'password' => 'required|same:confirm-password',
            'role' => ['required', Rule::notIn('Super-Admin')],
            'status' => 'boolean',
            'department_id' => 'nullable|integer',
            'designation_id' => 'nullable|integer',
            'date_of_joining' => 'nullable|date',
            'work_phone' => 'nullable|string',
            'phone' => 'nullable|string',
            'key' => 'nullable|string',
        ]);

        // If validation fails, return error
        if ($validator->fails()) {
            notify()->error($validator->errors()->first(), 'Error');
            return redirect()->back();
        }

        // Get the validated input
        $input = $request->all();

//        dd($input);

        // Convert empty fields to null if necessary
        $input['password'] = Hash::make($input['password']);
        $input['employee_id'] = $input['employee_id'] ?: null;
        $input['department_id'] = $input['department_id'] ?: null;
        $input['designation_id'] = $input['designation_id'] ?: null;
        $input['date_of_joining'] = $input['date_of_joining'] ?: null;
        $input['work_phone'] = $input['work_phone'] ?: null;
        $input['phone'] = $input['phone'] ?: null;

        // Create the new admin record
        $admin = Admin::create($input);
        $admin->assignRole($request->input('role'));

        // Notify success
        notify()->success('Staff created successfully');

        // Redirect to the staff index page
        return redirect()->route('admin.staff.index');
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return string
     */
    public function edit($id)
    {
        $staff = Admin::find($id);
        $roles = Role::whereNot('name', 'Super-Admin')->get();
        $departments = Department::with('children')->whereNull('parent_id')->get();
        $designations = Designation::with('children')->whereNull('parent_id')->get();
        $users = User::all(); // Fetch all users
        $ibGroups = IbGroup::all(); // Fetch all IB groups
        $attachedUsers = $staff->users; // Fetch attached users

        return view('backend.staff.edit', compact('staff', 'roles', 'departments', 'designations', 'users', 'ibGroups', 'attachedUsers'))->render();
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
                'name' => 'required',
                'email' => 'required|email|unique:admins,email,' . $id,
                'password' => 'nullable|same:confirm-password',
                'role' => ['nullable', Rule::notIn('Super-Admin')],
                'status' => 'boolean',
                'department' => 'nullable|exists:departments,id',
                'designation' => 'nullable|exists:designations,id',
                'ib_groups' => 'nullable|array',
            ]);

            if ($validator->fails()) {
                return response()->json(['success' => false, 'message' => $validator->errors()->first()], 422);
            }

            $input = $request->all();
            $input['employee_id'] = $request->input('employee_id') ?: null;
            $input['department_id'] = $request->input('department_id') ?: null;
            $input['designation_id'] = $request->input('designation_id') ?: null;
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
            // Save IB Groups

            $staff->update($input);

            if ($request->filled('role')) {
                DB::table('model_has_roles')->where('model_id', $id)->delete();
                $staff->assignRole($request->input('role'));
            }

            if (auth()->user()->hasRole('Super-Admin') && !$staff->hasRole('Super-Admin')) {
                // Get all users belonging to the given IB groups
//                dd($request->input('ib_groups', []));
                $allUsers = [];
                $ibUsers = User::whereIn('ib_group_id', $request->input('ib_groups', []))->pluck('id')->toArray();
//                dd($ibUsers);
                if (empty($ibUsers)) {
                    $staff->users()->sync($allUsers);
                } else {

                    // Get the complete referral network (including IB users)
                    $networkUsers = $this->getReferralNetwork($ibUsers);

                    // Merge IB group users with their referral network
                    $allUsers = array_unique(array_merge($ibUsers, $networkUsers));

                    // Sync users with the staff member
                    $staff->users()->sync($allUsers);

                    $input['ib_groups'] = $request->input('ib_groups', []);
                    $staff->update($input);


                }
            }


            $roles = Role::whereNot('name', 'Super-Admin')->get();
            $departments = Department::with('children')->whereNull('parent_id')->get();
            $designations = Designation::with('children')->whereNull('parent_id')->get();
            $users = User::all(); // Fetch all users
            $ibGroups = IbGroup::all();
            $attachedUsers = $staff->users; // Fetch attached users

            $updatedStaff = view('backend.staff.edit', compact('staff', 'roles', 'departments', 'designations', 'users', 'ibGroups', 'attachedUsers'))->render();

            return response()->json([
                'success' => true,
                'message' => 'Staff updated successfully!',
                'updatedHtml' => $updatedStaff
            ]);

        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'An error occurred: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Recursive function to fetch referral network.
     */
    private function getReferralNetwork(array $parentIds)
    {
        $allUsers = [];
//dd($parentIds);
        while (!empty($parentIds)) {
            // Get all users who have these parent IDs as ref_id
            $users = User::whereIn('ref_id', $parentIds)->pluck('id')->toArray();
            $allUsers = array_merge($allUsers, $users);
            $parentIds = $users; // Continue recursion with new found users
        }

        return $allUsers;
    }


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

        if ($request->status == 'disable') {

            if (Hash::check(request('one_time_password'), $user->password)) {
                $user->update([
                    'two_fa' => 0,
                ]);
                notify()->success(__('2Fa Authentication Disable successfully'));

                return redirect()->back();
            }

            notify()->warning(__('Wrong Your Password'));

            return redirect()->back();

        } elseif ($request->status == 'enable') {
            session([
                config('google2fa.session_var') => [
                    'auth_passed' => false,
                ],
            ]);

            $authenticator = app(Authenticator::class)->boot($request);
            if ($authenticator->isAuthenticated()) {

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
        $staff = Admin::find($id);
        if ($staff->getRoleNames()->first() === 'Super-Admin') {
            notify()->warning('Super admin not deleteble');
            return redirect()->back();
        }
        $staff->delete();

        notify()->success('staff deleted successfully');

        return redirect()->back();
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
