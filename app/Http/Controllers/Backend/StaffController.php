<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\Department;
use App\Models\Designation;
use Arr;
use DB;
use Hash;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use PragmaRX\Google2FALaravel\Support\Authenticator;
use Spatie\Permission\Models\Role;

class StaffController extends Controller
{
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
    public function index()
    {
        $staffs = Admin::paginate(10);

        return view('backend.staff.index', compact('staffs'));
    }

    public function create()
    {
        $roles = Role::whereNot('name', 'Super-Admin')->get();
        $departments = Department::with('children')->whereNull('parent_id')->get();
        $designations = Designation::with('children')->whereNull('parent_id')->get();
        return view('backend.staff.create', compact('roles','departments','designations'));
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
        ]);

        // If validation fails, return error
        if ($validator->fails()) {
            notify()->error($validator->errors()->first(), 'Error');
            return redirect()->back();
        }

        // Get the validated input
        $input = $request->all();

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
     * @param  int  $id
     * @return string
     */
    public function edit($id)
    {

        $roles = Role::whereNot('name', 'Super-Admin')->get();
        $staff = Admin::find($id);
        $staff->getRoleNames()->first();
        $departments = Department::with('children')->whereNull('parent_id')->get();
        $designations = Designation::with('children')->whereNull('parent_id')->get();
        return view('backend.staff.edit', compact('staff', 'roles','departments','designations'))->render();
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return RedirectResponse
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name'        => 'required',
            'email'       => 'required|email|unique:admins,email,' . $id,
            'password'    => 'same:confirm-password',
            'role'        => ['required', Rule::notIn('Super-Admin')],
            'status'      => 'boolean',
            'department'  => 'nullable|exists:departments,id',
            'designation' => 'nullable|exists:designations,id',
        ]);

        if ($validator->fails()) {
            notify()->error($validator->errors()->first(), 'Error');
            return redirect()->back();
        }

        // Get all request inputs
        $input = $request->all();

        // Map 'department' to 'department_id' and handle nullable values
        $input['employee_id'] = $request->input('employee_id') ?: null;
        $input['department_id'] = $request->input('department_id') ?: null;
        $input['designation_id'] = $request->input('designation_id') ?: null;

        // Remove 'department' and 'designation' from input to prevent mass assignment issues
        unset($input['department'], $input['designation']);

        // Handle password update
        if (!empty($input['password'])) {
            $input['password'] = Hash::make($input['password']);
        } else {
            $input = Arr::except($input, ['password']);
        }

        $staff = Admin::find($id);

        if ($staff->getRoleNames()->first() === 'Super-Admin') {
            notify()->warning('Super admin not changeable');
            return redirect()->back();
        }

        // Invalidate the user's session
        $this->invalidateUserSession($staff);

//        dd($input);
        // Update the admin record with correctly mapped input
        $staff->update($input);

        // Update role and relationships
        DB::table('model_has_roles')->where('model_id', $id)->delete();
        $staff->assignRole($request->input('role'));

        // Sync departments and designations if provided
//        $department = $request->input('department');
//        if (isset($department)) {
//            $staff->departments()->sync([$department]);
////        } else {
////            $staff->departments()->detach();
////        }
//
//        $designation = $request->input('designation');
//        if (isset($designation)) {
//            $staff->designations()->sync([$designation]);
//        } else {
//            $staff->designations()->detach();
//        }

        notify()->success('Staff updated successfully');
        return redirect()->route('admin.staff.index');
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


    public function security()
    {
        return view('backend.staff.security.index');
    }
    public function twoFaPin()
    {
        return view('backend.auth.two_fa_pin');
    }
    public function twoFa()
    {
        $user = \Auth::user();
        $google2fa = app('pragmarx.google2fa');
        $secret = $google2fa->generateSecretKey();
//dd($user,$google2fa,$secret);
        $user->update([
            'google2fa_secret' => $secret,
        ]);
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


}
