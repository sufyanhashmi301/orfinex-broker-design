<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\IbGroup;
use App\Traits\NotifyTrait;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class IBGroupController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\View\View|\Illuminate\Contracts\View\Factory
     */
    public function index()
    {
        $ibGroups = IbGroup::paginate(10); // Get paginated results
        return view('backend.ib_group.index', compact('ibGroups')); // Return the view with data
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Contracts\View\View|\Illuminate\Contracts\View\Factory
     */
    public function create()
    {
        return view('backend.ib_group.create'); // Return the create form view
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        // Validation for creating a new IbGroup
        $validator = Validator::make($request->all(), [
            'name' => 'required|unique:ib_groups,name',
            'status' => 'required|boolean',
        ]);

        if ($validator->fails()) {
            notify()->error($validator->errors()->first(), 'Error');
            return redirect()->back();
        }

        // Create the new IbGroup record
        $status = $request->input('status', 1); // Default status to active (1) if not provided
        $ibGroup = IbGroup::create($request->only(['name', 'desc']) + ['status' => $status]);

        notify()->success($ibGroup->name . ' ' . __('IB Group Created'));
        return redirect()->route('admin.ib-group.index');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Contracts\View\View|\Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse
     */
    public function edit($id)
    {
        $ibGroup = IbGroup::find($id);
        if (!$ibGroup) {
            notify()->error(__('IB Group not found'), 'Error');
            return redirect()->route('admin.ib-group.index');
        }
        return view('backend.ib_group.modal.__edit_form', compact('ibGroup'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $id)
    {
        // Validation for updating an existing IbGroup
        $validator = Validator::make($request->all(), [
            'name' => 'required|unique:ib_groups,name,' . $id,
            'status' => 'required|boolean',
        ]);

        if ($validator->fails()) {
            notify()->error($validator->errors()->first(), 'Error');
            return redirect()->back();
        }

        $ibGroup = IbGroup::find($id);
        if (!$ibGroup) {
            notify()->error(__('IB Group not found'), 'Error');
            return redirect()->route('admin.ib-group.index');
        }

        // Update the IB Group with the provided data
        $ibGroup->update($request->only(['name', 'desc', 'status']));

        notify()->success($ibGroup->name . ' ' . __('IB Group Updated'));
        return redirect()->route('admin.ib-group.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id)
    {
        $ibGroup = IbGroup::find($id);
        if ($ibGroup) {
            $ibGroup->delete();
            notify()->success(__('IB Group Deleted Successfully'));
        } else {
            notify()->error(__('IB Group not found'), 'Error');
        }

        return redirect()->route('admin.ib-group.index');
    }
}
