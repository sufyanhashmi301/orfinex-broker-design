<?php

namespace App\Http\Controllers\Backend;

use App\Exports\CompanyFormSubmissionsExport;
use App\Http\Controllers\Controller;
use App\Models\CompanyFormSubmission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\Facades\DataTables;

class CompanyFormSubmissionController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:branches-form-list', ['only' => ['pending', 'approved', 'rejected']]);
        $this->middleware('permission:branches-form-action', ['only' => ['actionModal', 'updateStatus']]);
        $this->middleware('permission:branches-form-export', ['only' => ['export']]);
    }

    public function pending()
    {
        return view('backend.company_submissions.pending');
    }

    public function approved()
    {
        return view('backend.company_submissions.approved');
    }

    public function rejected()
    {
        return view('backend.company_submissions.rejected');
    }

    public function data(Request $request, string $status)
    {
        $query = CompanyFormSubmission::with(['user'])
            ->when(in_array($status, ['pending','approved','rejected']), fn($q) => $q->where('status', $status));

        if ($request->ajax()) {
            if ($request->filled('search')) {
                $search = $request->input('search');
                $query->whereHas('user', function ($q) use ($search) {
                    $q->where(DB::raw("CONCAT(first_name,' ',last_name)"), 'like', "%{$search}%")
                        ->orWhere('username', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                });
            }
            if ($request->filled('created_at')) {
                $range = explode(' to ', str_replace(' - ', ' to ', $request->created_at));
                $from = $range[0] ?? null;
                $to = $range[1] ?? $range[0] ?? null;
                if ($from) $query->whereDate('created_at', '>=', $from);
                if ($to) $query->whereDate('created_at', '<=', $to);
            }

            return DataTables::of($query)
                ->editColumn('created_at', function ($row) {
                    return $row->created_at->format('Y-m-d H:i');
                })
                ->addColumn('username', function ($row) {
                    return view('backend.transaction.include.__user', ['user_id' => $row->user_id])->render();
                })
                ->addColumn('status', function ($row) {
                    $color = match ($row->status) {
                        'approved' => 'success',
                        'rejected' => 'danger',
                        default => 'warning',
                    };
                    return '<span class="badge bg-' . $color . '-500 text-white">' . ucfirst($row->status) . '</span>';
                })
                ->addColumn('action', function ($row) {
                    return '<button type="button" class="btn btn-sm submission-action-btn" data-id="' . $row->id . '"><iconify-icon icon="lucide:eye"></iconify-icon></button>';
                })
                ->rawColumns(['status', 'action', 'username'])
                ->make(true);
        }

        abort(404);
    }

    public function actionModal(int $id)
    {
        $submission = CompanyFormSubmission::with('user')->findOrFail($id);
        return view('backend.company_submissions.modal.__detail_modal', compact('submission'));
    }

    public function updateStatus(Request $request)
    {
        $request->validate([
            'id' => 'required|integer|exists:company_form_submissions,id',
            'status' => 'required|in:approved,rejected,pending',
        ]);
        $submission = CompanyFormSubmission::findOrFail($request->id);
        $submission->status = $request->status;
        $submission->save();
        return response()->json(['success' => true]);
    }

    public function export(Request $request, string $status)
    {
        $filename = 'company-form-submissions-' . $status . '-' . date('Y-m-d') . '.xlsx';
        return Excel::download(new CompanyFormSubmissionsExport($status, $request->only(['search','created_at'])), $filename);
    }
}



