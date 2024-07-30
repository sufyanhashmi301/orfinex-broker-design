<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreDepartmentRequest;
use App\Http\Requests\UpdateDepartmentRequest;
use App\Models\Department;
use App\Services\DepartmentService;
use Illuminate\Http\Request;

class DepartmentController extends Controller
{
    protected $departmentService;

    public function __construct(DepartmentService $departmentService)
    {
        $this->departmentService = $departmentService;
    }

    public function index()
    {
        $departments = Department::all();
        return view('backend.departments.index', compact('departments'));
    }

    public function create()
    {
        $departments = Department::all();
        return view('backend.departments.create', compact('departments'));
    }

    public function store(StoreDepartmentRequest $request)
    {
        $data = $request->validated();
        $data['parent_id'] = $data['parent_id'] !="" ?$data['parent_id'] : null;
      
        $this->departmentService->create($data);
        notify()->success(__('Department created successfully.'));
        return redirect()->route('admin.departments.index');
        
    }

    public function show(Department $department)
    {
        return view('departments.show', compact('department'));
    }

    public function edit(Department $department)
    {
        $departments = Department::where('id', '!=', $department->id)->get();
        return view('backend.departments.edit', compact('department', 'departments'));
    }

    public function update(UpdateDepartmentRequest $request, Department $department)
    { 
        $data = $request->validated();
        $data['parent_id'] = $data['parent_id'] != "" ?$data['parent_id']: null;  // Set to null if empty
        $this->departmentService->update($department, $data);
        notify()->success(__('Department updated successfully.'));
        return redirect()->route('admin.departments.index');
        
    }

    public function destroy(Department $department)
    {
        $this->departmentService->delete($department);
        notify()->success(__('Department deleted successfully.'));
        return redirect()->route('admin.departments.index');
        
    }
}
