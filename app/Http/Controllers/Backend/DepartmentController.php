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
        $descendants = $department->descendants()->pluck('id');
        $departments = Department::where('id', '!=', $department->id)
            ->whereNotIn('id', $descendants)
            ->get();
        return view('backend.departments.edit', compact('department', 'departments'));
    }

    public function update(UpdateDepartmentRequest $request, Department $department)
    { 
        $data = $request->validated();
        if ($department->children()->exists() && !is_null($data['parent_id'])) {
            notify()->error(__('Cannot reassign this department as it has child records.'));
            return redirect()->back();
        }
        $data['parent_id'] = $data['parent_id'] != "" ?$data['parent_id']: null;  // Set to null if empty
        $this->departmentService->update($department, $data);
        notify()->success(__('Department updated successfully.'));
        return redirect()->route('admin.departments.index');
        
    }

    public function destroy(Department $department)
    {
        if ($department->staff()->count() > 0) {
            notify()->error(__('This department cannot be deleted because staff is linked to it.'));
            return redirect()->route('admin.departments.index');
        }
        if ($department->children()->count() > 0) {
            notify()->error(__('This department cannot be deleted because child departments exist.'));
            return redirect()->route('admin.departments.index');
        }
        $this->departmentService->delete($department);
        notify()->success(__('Department deleted successfully.'));
        return redirect()->route('admin.departments.index');
        
    }
}
