<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use Illuminate\Http\Request;

class TeamController extends Controller
{
        public function __construct()
    {
        $this->middleware('permission:staff-team-list|staff-team-create', ['only' => ['manage']]);
        $this->middleware('permission:staff-team-create', ['only' => ['attach']]);
        $this->middleware('permission:staff-team-delete', ['only' => ['detach']]);

    }

   public function manage($staffId)
{
    $staff = Admin::with('teamMembers.roles')->findOrFail($staffId);
    
    $availableStaff = Admin::where('id', '!=', $staffId)
        ->whereDoesntHave('managingStaff', fn($q) => $q->where('manager_id', $staffId))
        ->whereDoesntHave('roles', function($query) {
            $query->where('name', 'Super-Admin'); // or 'superadmin' depending on your role name
        })
        ->with('roles') // eager load roles to prevent N+1 queries
        ->get();
        
    return view('backend.staff.team', compact('staff', 'availableStaff'));
}
    public function attach(Request $request, $staffId)
    {
        $request->validate(['staff_ids' => 'required|array']);
        
        $staff = Admin::findOrFail($staffId);
        $staff->teamMembers()->syncWithoutDetaching($request->staff_ids);
        
        notify()->success('Team members added successfully');
        return back();
    }

    public function detach($staffId, $memberId)
    {
        $staff = Admin::findOrFail($staffId);
        $detached = $staff->teamMembers()->detach($memberId);
        
        if ($detached) {
            notify()->success('Team member removed successfully');
        } else {
            notify()->error('Failed to remove team member');
        }
        
        return back();
    }
    
    public function bulkDetach(Request $request, $staffId)
    {
        $request->validate(['staff_ids' => 'required|string']);
        
        $staff = Admin::findOrFail($staffId);
        $memberIds = explode(',', $request->staff_ids);
        $detachedCount = $staff->teamMembers()->detach($memberIds);
        
        if ($detachedCount > 0) {
            $message = $detachedCount > 1 
                ? "$detachedCount team members removed successfully"
                : "Team member removed successfully";
            notify()->success($message);
        } else {
            notify()->error('No team members were removed');
        }
        
        return back();
    }
   


}