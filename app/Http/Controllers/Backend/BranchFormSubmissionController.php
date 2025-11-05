<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\BranchFormSubmission;
use App\Models\User;
use App\Models\Branch;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use App\Exports\BranchFormSubmissionsExport;
use Maatwebsite\Excel\Facades\Excel;

class BranchFormSubmissionController extends Controller
{
    public function __construct()
    {

    $this->middleware('permission:branches-form-list', ['only' => ['pending', 'approved', 'rejected']]);
    $this->middleware('permission:branches-form-action', ['only' => ['actionModal', 'updateStatus']]);
    $this->middleware('permission:branches-form-export', ['only' => ['export']]);
    }
    public function pending()
    {
        return view('backend.branch_submissions.pending');
    }

    public function approved()
    {
        return view('backend.branch_submissions.approved');
    }

    public function rejected()
    {
        return view('backend.branch_submissions.rejected');
    }

    public function data(Request $request, string $status)
    {
        $query = BranchFormSubmission::with(['user', 'branch'])
            ->when($status !== 'all', function ($q) use ($status) {
                $q->where('status', $status);
            });

        // Filters
        if ($request->filled('search')) {
            $search = $request->get('search');
            $query->whereHas('user', function ($q) use ($search) {
                $q->where(DB::raw("CONCAT(first_name,' ',last_name)"), 'like', "%{$search}%")
                  ->orWhere('username', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }
        if ($request->filled('branch')) {
            $branchSearch = $request->get('branch');
            $query->whereHas('branch', function ($q) use ($branchSearch) {
                $q->where('name', 'like', "%{$branchSearch}%");
            });
        }
        if ($request->filled('created_at')) {
            // Expecting flatpickr range "YYYY-MM-DD to YYYY-MM-DD" or "YYYY-MM-DD to YYYY-MM-DD"
            $range = explode(' to ', str_replace(' - ', ' to ', $request->get('created_at')));
            $from = $range[0] ?? null;
            $to = $range[1] ?? $range[0] ?? null;
            if ($from) {
                $query->whereDate('created_at', '>=', $from);
            }
            if ($to) {
                $query->whereDate('created_at', '<=', $to);
            }
        }

        return DataTables::of($query)
            ->editColumn('created_at', function ($row) {
                return $row->created_at->format('Y-m-d H:i');
            })
            ->addColumn('username', function ($row) {
                return view('backend.transaction.include.__user', ['user_id' => $row->user_id])->render();
            })
            ->addColumn('branch_name', function ($row) {
                return $row->branch ? e($row->branch->name) : ('#'.$row->branch_id);
            })
            ->addColumn('status', function ($row) {
                if ($row->status === 'approved') {
                    return '<span class="badge bg-success-500 text-success-500 bg-opacity-30">Approved</span>';
                } elseif ($row->status === 'rejected') {
                    return '<span class="badge bg-danger-500 text-danger-500 bg-opacity-30">Rejected</span>';
                }
                // Pending - use solid warning with white text (matches app style)
                return ' <div class="badge badge-warning capitalize">Pending
        </div>';
            })
            ->addColumn('action', function ($row) {
                $url = route('admin.branch-form-submissions.action.modal', $row->id);
                return '<button type="button" class="action-btn submission-action-btn" data-id="'.$row->id.'"><iconify-icon icon="lucide:eye"></iconify-icon></button>';
            })
            ->rawColumns(['status', 'action', 'username'])
            ->make(true);
    }

    public function actionModal(int $id)
    {
        $submission = BranchFormSubmission::with(['user', 'branch'])->findOrFail($id);
        return view('backend.branch_submissions.modal.__detail_modal', compact('submission'));
    }

    public function updateStatus(Request $request)
    {
        $request->validate([
            'id' => 'required|integer|exists:branch_form_submissions,id',
            'status' => 'required|in:approved,rejected,pending',
        ]);
        $submission = BranchFormSubmission::findOrFail($request->id);
        $submission->status = $request->status;
        $submission->save();

        if ($submission->status === 'approved') {
            // assign branch on approve
            setUserBranchId($submission->user_id, $submission->branch_id);
        }

        return response()->json(['success' => true]);
    }

    public function export(Request $request, string $status)
    {
        $filename = 'branch-form-submissions-'.$status.'-'.date('Y-m-d').'.xlsx';
        return Excel::download(new BranchFormSubmissionsExport($status, $request->only(['search','branch','created_at'])), $filename);
    }
}


