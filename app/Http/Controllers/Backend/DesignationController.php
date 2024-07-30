<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreDesignationRequest;
use App\Http\Requests\UpdateDesignationRequest;
use App\Models\Designation;
use App\Services\DesignationService;
use Illuminate\Http\Request;

class DesignationController extends Controller
{
    protected $designationService;

    public function __construct(DesignationService $designationService)
    {
        $this->designationService = $designationService;
    }

    public function index()
    {
        $designations = Designation::all();
        return view('backend.designations.index', compact('designations'));
    }

    public function create()
    {
        $designations = Designation::all();
        return view('backend.designations.create', compact('designations'));
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
        $designations = Designation::where('id', '!=', $designation->id)->get();
        return view('backend.designations.edit', compact('designation', 'designations'));
    }

    public function update(UpdateDesignationRequest $request, Designation $designation)
    { 
        $data = $request->validated();
        $data['parent_id'] = $data['parent_id'] != "" ?$data['parent_id']: null;  // Set to null if empty
        $this->designationService->update($designation, $data);
        notify()->success(__('Designation updated successfully.'));
        return redirect()->route('admin.designations.index');
        
    }

    public function destroy(Designation $designation)
    {
        $this->designationService->delete($designation);
        notify()->success(__('Designation deleted successfully.'));
        return redirect()->route('admin.designations.index');
        
    }
}
