<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreDepartmentRequest;
use App\Http\Requests\UpdateDepartmentRequest;
use App\Models\Department;
use App\Services\DepartmentService;
use Illuminate\Http\Request;
use DataTables;

class DepartmentController extends Controller
{
    protected $departmentService;

    public function __construct(DepartmentService $departmentService)
    {
         $this->middleware('permission:departments-list', ['only' => ['index']]);
         $this->middleware('permission:department-create', ['only' => ['store']]);
         $this->middleware('permission:department-edit', ['only' => ['update']]);
         $this->middleware('permission:department-delete', ['only' => ['destroy']]);
        $this->departmentService = $departmentService;
    }

    public function index(Request $request)
    {
        // Fetch the data
        $data = Department::with('parent')->latest('updated_at');

        // Get the count of records
        $dataCount = $data->count();

        if ($request->ajax()) {
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('name', 'backend.departments.include.__name')
                ->addColumn('parent_category', function ($department) {
                    return $department->parent->name ?? '-';
                })
                ->addColumn('status', 'backend.departments.include.__status')
                ->addColumn('action', 'backend.departments.include.__action')
                ->rawColumns(['name','status', 'parent_category', 'action'])
                ->make(true);
        }

        return view('backend.departments.index', compact('dataCount'));

    }

    public function create()
    {
        $departments = Department::where('parent_id',null)->get();
        return response()->json(['departments'=>$departments]);

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
        ->where('parent_id',null)
            ->whereNotIn('id', $descendants)
            ->get();
            return view('backend.departments.include.__edit_form', compact('departments', 'department'))->render();

    }

    public function update(UpdateDepartmentRequest $request, Department $department)
    {
        $data = $request->validated();

        if ($department->children()->exists() && $data['parent_id'] !== "") {
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
