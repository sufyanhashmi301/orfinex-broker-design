<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\Department;
use App\Models\Designation;
use App\Models\User;
use App\Models\IbGroup;
use App\Models\ForexSchema;
use App\Models\ForexAccount;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Spatie\Permission\Models\Role;
use DataTables;

class UserAttachmentController extends Controller
{

    public function __construct()
    {
        $this->middleware('permission:staff-attach-users-list|staff-attach-users-create', ['only' => ['index','getAttachedUsers','attachUser']]);
        $this->middleware('permission:staff-attach-users-create', ['only' => ['attachUser']]);
        $this->middleware('permission:staff-attach-users-delete', ['only' => ['detachUser']]);

    }
    public function index($staffId)
    {
        $staff = Admin::find($staffId);
        $roles = Role::whereNot('name', 'Super-Admin')->get();
        $users = User::whereNotIn('id', $staff->users->pluck('id')->toArray())->get();
        $ibGroups = IbGroup::all();
        $schemas = ForexSchema::orderBy('priority','asc')->traderType()->get();

        return view('backend.staff.attach-user', compact('staff', 'roles', 'users', 'ibGroups', 'schemas'));

    }

    public function getAttachedUsers(Request $request, $staffId)
    {
        if ($request->ajax()) {

            $staff = Admin::find($staffId);
            $attachedUsers = $staff->users;

            return DataTables::of($attachedUsers)
                ->addIndexColumn()
                ->addColumn('email', function ($user) {
                    return '<span class="lowercase">'.$user->email.'</span>';
                })
                ->addColumn('action', function ($user) use ($staff) {
                    return view('backend.staff.include.__detach_btn', compact('user', 'staff'));
                })
                ->rawColumns(['email', 'action'])
                ->make(true);
        }
    }

    public function attachUser(Request $request, $staffId)
    {
        $staff = Admin::find($staffId);

        $input = $request->all();
        $validator = Validator::make($input, [
            'ib_groups' => 'nullable|array',
            'account_types' => 'nullable|array',
        ]);

        if ($validator->fails()) {
            notify()->error($validator->errors()->first(), 'Error');
            return redirect()->back();
        }

        if (auth()->user()->hasRole('Super-Admin') && !$staff->hasRole('Super-Admin')) {

            $userIds = $request->input('user_ids', []);

            // Retrieve the current ib_groups and account_types from the database
            $currentIbGroups = $staff->ib_groups ?? [];
            $currentAccountTypes = $staff->account_types ?? [];

            $newIbGroups = $request->input('ib_groups', []);
            $newAccountTypes = $request->input('account_types', []);

            $previousIbGroups = array_diff($currentIbGroups, $newIbGroups);
            $previousAccountTypes = array_diff($currentAccountTypes, $newAccountTypes);

            // Get all users belonging to the given IB groups
            if (in_array('all', $request->input('ib_groups', []))) {
                $allIbGroups = IbGroup::pluck('id')->toArray();
                $ibUsers = User::whereIn('ib_group_id', $allIbGroups)->pluck('id')->toArray();
            } else {
                $ibUsers = User::whereIn('ib_group_id', $newIbGroups)->pluck('id')->toArray();
            }

            if (in_array('all', $request->input('account_types', []))) {
                $accountTypeUsers = ForexAccount::with('user')->get()->pluck('user.id')->toArray();
            } else {
                $accountTypeUsers = ForexAccount::whereIn('forex_schema_id', $newAccountTypes)->with('user')->get()->pluck('user.id')->toArray();
            }

            $networkUsers = $this->getReferralNetwork($ibUsers);
            $allUsers = array_unique(array_merge($ibUsers, $networkUsers, $accountTypeUsers, $userIds));

            // $staff->users()->attach($allUsers);
            $staff->users()->syncWithoutDetaching($allUsers);

            if (!empty($previousIbGroups)) {
                $ibUsers = User::whereIn('ib_group_id', $previousIbGroups)->pluck('id')->toArray();
                $networkUsers = $this->getReferralNetwork($ibUsers);
                $allUsers = array_unique(array_merge($ibUsers, $networkUsers));

                $staff->users()->detach($allUsers);
            }

            if (!empty($previousAccountTypes)) {
                $accountTypeUsers = ForexAccount::whereIn('forex_schema_id', $previousAccountTypes)->with('user')->get()->pluck('user.id')->toArray();
                $staff->users()->detach($accountTypeUsers);
            }

            $input['ib_groups'] = $newIbGroups;
            $input['account_types'] = $newAccountTypes;
            $staff->update($input);

            notify()->success('User attached successfully!');
            return redirect()->back();
        }

    }

    private function getReferralNetwork(array $parentIds)
    {
        $allUsers = [];
        while (!empty($parentIds)) {
            // Get all users who have these parent IDs as ref_id
            $users = User::whereIn('ref_id', $parentIds)->pluck('id')->toArray();
            $allUsers = array_merge($allUsers, $users);
            $parentIds = $users; // Continue recursion with new found users
        }

        return $allUsers;
    }

    public function detachUser(Request $request, $staffId)
    {
        $staff = Admin::findOrFail($staffId);
        $userIds = $request->input('user_id') ? [$request->input('user_id')] : explode(',', $request->input('user_ids', ''));
    
        try {
            $detachedCount = $staff->users()->detach($userIds);
    
            if ($detachedCount > 0) {
                $message = count($userIds) > 1 
                    ? 'Users detached successfully!'
                    : 'User detached successfully!';
                notify()->success($message);
            } else {
                $message = count($userIds) > 1 
                    ? 'No users were detached'
                    : 'User was not detached';
                notify()->error($message);
            }
    
        } catch (\Exception $e) {
            notify()->error('Error detaching users: ' . $e->getMessage());
        }
    
        return redirect()->back();
    }
}
