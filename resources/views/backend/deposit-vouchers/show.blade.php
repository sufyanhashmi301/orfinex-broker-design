@extends('backend.layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Deposit Voucher Details</h3>
                    <div class="card-tools">
                        <a href="{{ route('admin.deposit-vouchers.index') }}" class="btn btn-default btn-sm">
                            <i class="fas fa-arrow-left"></i> Back to List
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table table-bordered">
                                <tr>
                                    <th style="width: 200px;">Title</th>
                                    <td>{{ $depositVoucher->title }}</td>
                                </tr>
                                <tr>
                                    <th>Amount</th>
                                    <td>{{ number_format($depositVoucher->amount, 2) }}</td>
                                </tr>
                                <tr>
                                    <th>Expiry Date</th>
                                    <td>{{ $depositVoucher->expiry_date->format('Y-m-d H:i') }}</td>
                                </tr>
                                <tr>
                                    <th>Status</th>
                                    <td>
                                        <span class="badge badge-{{ $depositVoucher->status === 'active' ? 'success' : ($depositVoucher->status === 'used' ? 'info' : 'danger') }}">
                                            {{ ucfirst($depositVoucher->status) }}
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Used By</th>
                                    <td>{{ $depositVoucher->user ? $depositVoucher->user->name : 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <th>Used Date</th>
                                    <td>{{ $depositVoucher->used_date ? $depositVoucher->used_date->format('Y-m-d H:i') : 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <th>Modal</th>
                                    <td>{{ $depositVoucher->modal ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <th>Description</th>
                                    <td>{{ $depositVoucher->description ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <th>Created At</th>
                                    <td>{{ $depositVoucher->created_at->format('Y-m-d H:i') }}</td>
                                </tr>
                                <tr>
                                    <th>Updated At</th>
                                    <td>{{ $depositVoucher->updated_at->format('Y-m-d H:i') }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                    <div class="mt-3">
                        <a href="{{ route('admin.deposit-vouchers.edit', $depositVoucher) }}" class="btn btn-primary">
                            <i class="fas fa-edit"></i> Edit Voucher
                        </a>
                        <form action="{{ route('admin.deposit-vouchers.destroy', $depositVoucher) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this voucher?')">
                                <i class="fas fa-trash"></i> Delete Voucher
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 