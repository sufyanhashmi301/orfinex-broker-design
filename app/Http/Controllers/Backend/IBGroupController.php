<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\ForexSchema;
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

    public function __construct()
     {
         $this->middleware('permission:ib-group-list|ib-group-create|ib-group-edit', ['only' => ['index', 'store']]);
         $this->middleware('permission:ib-group-create', ['only' => ['store']]);
         $this->middleware('permission:ib-group-edit', ['only' => ['update']]);
         $this->middleware('permission:ib-group-delete', ['only' => ['destroy']]);
     }


    public function index()
    {
        $ibGroups = IbGroup::with('forexSchemas')->paginate(10); // Load related forexSchemas
        $activeForexSchemas = ForexSchema::active()->traderType()  // Use the defined scope for active schemas
        ->orderBy('priority', 'asc')
            ->get();
        return view('backend.ib_group.index', compact('ibGroups','activeForexSchemas')); // Return the view with data


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
        $validator = Validator::make($request->all(), [
            'name' => 'required|unique:ib_groups,name',
            'status' => 'required|boolean',
            'forex_schema_id' => 'nullable|exists:forex_schemas,id',
        ]);

        if ($validator->fails()) {
            notify()->error($validator->errors()->first(), 'Error');
            return redirect()->back();
        }

        $forexSchemaId = $request->input('forex_schema_id');
//        dd($forexSchemaId);
        if ($forexSchemaId) {
            $forexSchema = ForexSchema::find($forexSchemaId);
            if ($forexSchema->ib_group_id) {
                notify()->error(__('This Forex Schema is already attached to IB Group: ') . $forexSchema->ibGroup->name, 'Error');
                return redirect()->back();
            }
        }

        $ibGroup = IbGroup::create($request->only(['name', 'desc']) + ['status' => $request->input('status', 1)]);

        if ($forexSchemaId) {

            $forexSchema->update(['ib_group_id' => $ibGroup->id]); // Attach the schema
        }
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
        $activeForexSchemas = ForexSchema::active()->traderType()  // Use the defined scope for active schemas
        ->orderBy('priority', 'asc')
            ->get();
        return view('backend.ib_group.modal.__edit_form', compact('ibGroup','activeForexSchemas'));
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
        $validator = Validator::make($request->all(), [
            'name' => 'required|unique:ib_groups,name,' . $id,
            'status' => 'required|boolean',
            'forex_schema_id' => 'nullable|exists:forex_schemas,id',
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

        $forexSchemaId = $request->input('forex_schema_id');
        if ($forexSchemaId) {
            $forexSchema = ForexSchema::find($forexSchemaId);
            if ($forexSchema->ib_group_id && $forexSchema->ib_group_id != $ibGroup->id) {
                notify()->error(__('This Forex Schema is already attached to IB Group: ') . $forexSchema->ibGroup->name, 'Error');
                return redirect()->back();
            }

            $forexSchema->update(['ib_group_id' => $ibGroup->id]); // Reattach the schema
        }

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
