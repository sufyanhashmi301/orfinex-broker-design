<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\DepositVoucher;
use DataTables;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class DepositVoucherController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:deposit-voucher-list', ['only' => ['index']]);
        $this->middleware('permission:deposit-voucher-create', ['only' => ['store']]);
        $this->middleware('permission:deposit-voucher-edit', ['only' => ['update']]);
        $this->middleware('permission:deposit-voucher-delete', ['only' => ['destroy']]);
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = DepositVoucher::with('user')->latest();

            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('title', 'backend.deposit-vouchers.include.__title')
                ->addColumn('amount', function ($voucher) {
                    return number_format($voucher->amount, 2);
                })
                ->addColumn('expiry_date', function ($voucher) {
                    return $voucher->expiry_date->format('Y-m-d H:i');
                })
                ->addColumn('status', 'backend.deposit-vouchers.include.__status')
                ->addColumn('used_by', 'backend.deposit-vouchers.include.__user')
                ->addColumn('action', 'backend.deposit-vouchers.include.__action')
                ->rawColumns(['title', 'status', 'used_by', 'action'])
                ->make(true);
        }

        return view('backend.deposit-vouchers.index');
    }

    public function create()
    {
        return view('backend.deposit-vouchers.include.__create');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0',
            'expiry_date' => 'required|date|after:now',
            'description' => 'nullable|string',
            'modal' => 'nullable|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        try {
            // Generate a unique 6-digit code
            do {
                $code = Str::upper(Str::random(6));
            } while (DepositVoucher::where('code', $code)->exists());
    
            DepositVoucher::create([
                'title' => $request->title,
                'code' => $code,
                'amount' => $request->amount,
                'expiry_date' => $request->expiry_date,
                'description' => $request->description,
                'modal' => $request->modal,
            ]);
    
            notify()->success(__('Deposit voucher created successfully.'));
            return redirect()->route('admin.deposit-vouchers.index');
        } catch (\Exception $e) {
            report($e);
            return back()->with('error', 'Something went wrong while creating the voucher.');
        }
    }

    public function show(DepositVoucher $depositVoucher)
    {
        return view('backend.deposit-vouchers.show', compact('depositVoucher'));
    }

    public function edit(DepositVoucher $depositVoucher)
    {
        return view('backend.deposit-vouchers.include.__edit_form', compact('depositVoucher'))->render();
    }

    public function update(Request $request, DepositVoucher $depositVoucher)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0',
            'expiry_date' => 'required|date',
            'description' => 'nullable|string',
            'status' => 'required|in:active,used,expired',
            'modal' => 'nullable|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $depositVoucher->update($request->all());
        notify()->success(__('Deposit voucher updated successfully.'));
        return redirect()->route('admin.deposit-vouchers.index');
    }

    public function destroy(DepositVoucher $depositVoucher)
    {
        if ($depositVoucher->status === 'used') {
            notify()->error(__('Cannot delete a used voucher.'));
            return redirect()->route('admin.deposit-vouchers.index');
        }

        $depositVoucher->delete();
        notify()->success(__('Deposit voucher deleted successfully.'));
        return redirect()->route('admin.deposit-vouchers.index');
    }
} 