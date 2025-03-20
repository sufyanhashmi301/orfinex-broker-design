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

class UserAttachmentController extends Controller
{
    public function index($staffId)
    {
        $staff = Admin::find($staffId);
        $roles = Role::whereNot('name', 'Super-Admin')->get();
        $users = User::whereNotIn('id', $staff->users->pluck('id')->toArray())->get();
        $ibGroups = IbGroup::all();
        $schemas = ForexSchema::orderBy('priority','asc')->traderType()->get();
        $attachedUsers = $staff->users;

        return view('backend.staff.attach-user', compact('staff', 'roles', 'users', 'ibGroups', 'schemas', 'attachedUsers'));

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

            // Optionally log or notify about the changes
            notify()->info('Previous IB Groups: ' . implode(', ', $previousIbGroups) . ' | Previous Account Types: ' . implode(', ', $previousAccountTypes), 'Previous Values');

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
        $userId = $request->input('user_id');

        // Detach the user from the staff
        $staff->users()->detach($userId);


        notify()->success('User detached successfully!');
        return redirect()->back();
    }
}
