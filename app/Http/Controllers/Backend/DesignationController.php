<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreDesignationRequest;
use App\Http\Requests\UpdateDesignationRequest;
use App\Models\Designation;
use DataTables;
use App\Services\DesignationService;
use Illuminate\Http\Request;

class DesignationController extends Controller
{
    protected $designationService;

    public function __construct(DesignationService $designationService)
    {
        $this->designationService = $designationService;
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Designation::latest('updated_at');

            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('name', 'backend.designations.include.__name')
                ->addColumn('status', 'backend.designations.include.__status')
                ->addColumn('action', 'backend.designations.include.__action')
                ->rawColumns(['name','status', 'action'])
                ->make(true);
        }

        return view('backend.designations.index');
        
    }

    public function create()
    {
        $designations = Designation::where('parent_id',null)->get();
        return response()->json(['designations'=>$designations]);
        
    }

    public function store(StoreDesignationRequest $request)
    {
        $data = $request->validated();
        $data['parent_id'] = $data['parent_id'] !="" ?$data['parent_id'] : null;
        $this->designationService->create($data);
        notify()->success(__('Designation created successfully.'));
        return redirect()->route('admin.designations.index');
        
    }

    public function show(Designation $designation)
    {
        return view('backend.designations.show', compact('designation'));
    }

    public function edit(Designation $designation)
    {
        $descendants = $designation->descendants()->pluck('id');
        $designations = Designation::where('id', '!=', $designation->id)
        ->where('parent_id',null)
            ->whereNotIn('id', $descendants)
            ->get();
            return view('backend.designations.include.__edit_form', compact('designation', 'designations'))->render();

    }

    public function update(UpdateDesignationRequest $request, Designation $designation)
    { 
        $data = $request->validated();
        if ($designation->children()->exists() && !is_null($data['parent_id'])) {
            notify()->error(__('Cannot reassign this designation as it has child records.'));
            return redirect()->back();
        }
        $data['parent_id'] = $data['parent_id'] != "" ?$data['parent_id']: null;  // Set to null if empty
        $this->designationService->update($designation, $data);
        notify()->success(__('Designation updated successfully.'));
        return redirect()->route('admin.designations.index');
        
    }

    public function destroy(Designation $designation)
    {
        if ($designation->staff()->count() > 0) {
            notify()->error(__('This designation cannot be deleted because staff is linked to it.'));
            return redirect()->route('admin.designations.index');
        }
        if ($designation->children()->count() > 0) {
            notify()->error(__('This designation cannot be deleted because child designations exist.'));
            return redirect()->route('admin.designations.index');
        }
        $this->designationService->delete($designation);
        notify()->success(__('Designation deleted successfully.'));
        return redirect()->route('admin.designations.index');
        
    }
}
