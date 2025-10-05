<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Branch;
use App\Models\User;
use App\Models\Admin;
use App\Traits\NotifyTrait;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\BranchExport;

class BranchController extends Controller
{
    use NotifyTrait;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\View\View|\Illuminate\Contracts\View\Factory
     */
    public function __construct()
    {
        $this->middleware('permission:branch-list|branch-create|branch-edit', ['only' => ['index', 'store']]);
        $this->middleware('permission:branch-create', ['only' => ['store']]);
        $this->middleware('permission:branch-edit', ['only' => ['update']]);
        $this->middleware('permission:branch-delete', ['only' => ['destroy']]);
    }

    public function index(Request $request)
    {
        $query = Branch::query();
        
        if ($request->ajax() || $request->hasAny(['global_search', 'status'])) {
            // Global search
            if ($request->filled('global_search')) {
                $search = $request->global_search;
                $query->where(function($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                      ->orWhere('code', 'like', "%{$search}%");
                });
            }

            // Filter by status
            if ($request->filled('status')) {
                $query->where('status', $request->status);
            }
        }

        $branches = $query->withCount(['users', 'admins'])->paginate(10);

        if ($request->ajax()) {
            $html = view('backend.branch.index', compact('branches'))->render();
            return response()->json(['html' => $html]);
        }

        return view('backend.branch.index', compact('branches'));
    }

    public function export(Request $request)
    {
        $query = Branch::query();

        // Global search
        if ($request->filled('global_search')) {
            $search = $request->global_search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('code', 'like', "%{$search}%");
            });
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $branches = $query->get();

        return Excel::download(new BranchExport($branches), 'branches-' . date('Y-m-d') . '.xlsx');
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
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:10|unique:branches,code',
            'status' => 'required|boolean',
        ]);

        if ($validator->fails()) {
            notify()->error($validator->errors()->first(), 'Error');
            return redirect()->back();
        }

        // Create the Branch
        $branch = Branch::create($request->only(['name', 'code', 'status']));

        notify()->success($branch->name . ' ' . __('Branch Created Successfully'));
        return redirect()->route('admin.branches.index');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Contracts\View\View|\Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse
     */
    public function edit($id)
    {
        $branch = Branch::find($id);
        if (!$branch) {
            notify()->error(__('Branch not found'), 'Error');
            return redirect()->route('admin.branches.index');
        }

        return view('backend.branch.modal.__edit_form', compact('branch'));
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
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:10|unique:branches,code,' . $id,
            'status' => 'required|boolean',
        ]);

        if ($validator->fails()) {
            notify()->error($validator->errors()->first(), 'Error');
            return redirect()->back();
        }

        $branch = Branch::find($id);
        if (!$branch) {
            notify()->error(__('Branch not found'), 'Error');
            return redirect()->route('admin.branches.index');
        }

        // Update Branch details
        $branch->update($request->only(['name', 'code', 'status']));

        notify()->success($branch->name . ' ' . __('Branch Updated Successfully'));
        return redirect()->route('admin.branches.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Request $request, $id)
    {
        try {
            $branch = Branch::find($id);
            
            if (!$branch) {
                return response()->json([
                    'success' => false,
                    'message' => 'Branch not found'
                ], 404);
            }
            
            // Check for attached users and admins
            $userCount = $branch->users()->count();
            $adminCount = $branch->admins()->count();
            
            if ($request->check_usage) {
                return response()->json([
                    'user_count' => $userCount,
                    'admin_count' => $adminCount,
                    'total_count' => $userCount + $adminCount
                ]);
            }
            
            if ($userCount > 0 || $adminCount > 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'Cannot delete branch because there are users or staff still assigned to it.'
                ], 422);
            }
            
            // Proceed with deletion
            $branch->delete();
            
            return response()->json([
                'success' => true,
                'message' => __('Branch Deleted Successfully')
            ]);
            
        } catch (\Exception $e) {
            \Log::error("Branch Deletion Error: " . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while processing your request.'
            ], 500);
        }
    }

}