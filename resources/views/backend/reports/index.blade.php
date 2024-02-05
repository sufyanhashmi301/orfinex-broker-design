@extends('backend.layouts.app')
@section('title')
    {{ __('Reports') }}
@endsection
@section('style')
    <style>
        .site-input-groups {
            min-width: 210px;
            max-width: 100%;
        }
        .form-select-sm {
            padding-top: 0.25rem !important;
            padding-bottom: 0.25rem !important;
            padding-left: 0.5rem !important;
            font-size: .875rem !important;
            border-radius: 0.2rem !important;
            height: unset !important;
        }
        .form-control {
            border: 2px solid #e5e8f2;
        }
        .form-control:focus {
            box-shadow: none;
            border: 2px solid rgba(94, 63, 201, 0.5);
        }
        .header-actions .btn {
            padding: .25rem .5rem;
            font-size: .75rem;
        }
        .header-actions .btn svg, .btn svg {
            width: 16px;
            height: 16px;
            margin-top: -2px;
        }
        .site-table .table tbody tr td {
            font-size: 13px;
            line-height: 2;
        }
        .tag svg {
            width: 14px;
            height: 14px;
        }
        .input-group-text {
            padding: 0.25rem 0.75rem;
            font-size: .75rem;
        }
        #configureModal .btn-close {
            font-size: 13px;
        }
    </style>
@endsection
@section('content')
    <div class="main-content">
        <div class="page-title">
            <div class="container-fluid">
                <div class="row">
                    <div class="col">
                        <div class="title-content">
                            <h2 class="title">{{ __('All Reports') }}</h2>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="container-fluid">
            <div class="site-card">
                <form action="">
                    <div class="site-card-header flex-wrap flex-lg-nowrap align-items-center border-0 py-4">
                        <div class="row row-cols-md-auto g-3 align-items-center">
                            <div class="col-12">
                                <div class="site-input-groups mb-0">
                                    <label class="visually-hidden" for="inlineFormSelectPref">Preference</label>
                                    <select class="form-select form-select-sm" id="inlineFormSelectPref">
                                        <option>Choose...</option>
                                        <option value="1" selected>Transactions</option>
                                        <option value="2">Two</option>
                                        <option value="3">Three</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="site-input-groups mb-0">
                                    <label class="visually-hidden" for="inlineFormInputGroupUsername">Username</label>
                                    <input type="date" name="" class="form-control form-control-sm date">
                                </div>
                            </div>
                        </div>
                        <div class="header-actions">
                            <button type="submit" class="btn btn-success">
                                <i icon-name="filter" class="text-sm"></i>
                                <span class="ms-1">Apply Filters</span>
                            </button>
                            <a href="" class="btn btn-dark">
                                <i icon-name="download-cloud" class="text-sm"></i>
                                <span class="ms-1">Download</span>
                            </a>
                            <button type="button" class="btn btn-light" data-bs-toggle="modal" data-bs-target="#configureModal">
                                <i icon-name="wrench" class="text-sm"></i>
                                <span class="ms-1">Configure</span>
                            </button>
                        </div>
                    </div>
                </form>
                <div class="site-card-body pt-0">
                    <div class="site-table table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th scope="col" class="py-2">User</th>
                                    <th scope="col" class="py-2">Email</th>
                                    <th scope="col" class="py-2">Transaction ID</th>
                                    <th scope="col" class="py-2">Type</th>
                                    <th scope="col" class="py-2">Amount</th>
                                    <th scope="col" class="py-2">Gateway</th>
                                    <th scope="col" class="py-2">Status</th>
                                    <th scope="col" class="py-2">Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr class="odd">
                                    <td class="py-2">
                                        <span class="fw-bold">NaeemAli9338</span>
                                    </td>
                                    <td class="py-2">test@test.com</td>
                                    <td class="py-2">TRXUG0ZGH1XUR</td>
                                    <td class="py-2">Signup Bonus</td>
                                    <td class="py-2">
                                        <strong class="green-color">+8 USD</strong>
                                    </td>
                                    <td class="py-2">System</td>
                                    <td class="py-2">
                                        Success
                                    </td>
                                    <td class="py-2">Jan 01 2024 12:17</td>
                                </tr>
                                <tr class="even">
                                    <td class="py-2">
                                        <span class="fw-bold">NaeemMunir2507</span>
                                    </td>
                                    <td class="py-2">bo.augustin@falkcia.com</td>
                                    <td class="py-2">TRXD8LJHRWUWM</td>
                                    <td class="py-2">Signup Bonus</td>
                                    <td class="py-2">
                                        <strong class="green-color">+8 USD</strong>
                                    </td>
                                    <td class="py-2">System</td>
                                    <td class="py-2">Success</td>
                                    <td class="py-2">Jan 01 2024 11:57</td>
                                </tr>
                                <tr class="even">
                                    <td class="py-2">
                                        <span class="fw-bold">test23945</span>
                                    </td>
                                    <td class="py-2">test2@gmail.com</td>
                                    <td class="py-2">TRXFCWBHCYYJE</td>
                                    <td class="py-2">Signup Bonus</td>
                                    <td class="py-2">
                                        <strong class="green-color">+8 USD</strong>
                                    </td>
                                    <td class="py-2">System</td>
                                    <td class="py-2">Success</td>
                                    <td class="py-2">Dec 18 2023 11:32</td>
                                </tr>
                                <tr class="odd">
                                    <td class="py-2">
                                        <span class="fw-bold">NaeemAli9338</span>
                                    </td>
                                    <td class="py-2">test@test.com</td>
                                    <td class="py-2">TRXUG0ZGH1XUR</td>
                                    <td class="py-2">Signup Bonus</td>
                                    <td class="py-2">
                                        <strong class="green-color">+8 USD</strong>
                                    </td>
                                    <td class="py-2">System</td>
                                    <td class="py-2">
                                        Success
                                    </td>
                                    <td class="py-2">Jan 01 2024 12:17</td>
                                </tr>
                                <tr class="even">
                                    <td class="py-2">
                                        <span class="fw-bold">NaeemMunir2507</span>
                                    </td>
                                    <td class="py-2">bo.augustin@falkcia.com</td>
                                    <td class="py-2">TRXD8LJHRWUWM</td>
                                    <td class="py-2">Signup Bonus</td>
                                    <td class="py-2">
                                        <strong class="green-color">+8 USD</strong>
                                    </td>
                                    <td class="py-2">System</td>
                                    <td class="py-2">Success</td>
                                    <td class="py-2">Jan 01 2024 11:57</td>
                                </tr>
                                <tr class="even">
                                    <td class="py-2">
                                        <span class="fw-bold">test23945</span>
                                    </td>
                                    <td class="py-2">test2@gmail.com</td>
                                    <td class="py-2">TRXFCWBHCYYJE</td>
                                    <td class="py-2">Signup Bonus</td>
                                    <td class="py-2">
                                        <strong class="green-color">+8 USD</strong>
                                    </td>
                                    <td class="py-2">System</td>
                                    <td class="py-2">Success</td>
                                    <td class="py-2">Dec 18 2023 11:32</td>
                                </tr>
                                <tr class="odd">
                                    <td class="py-2">
                                        <span class="fw-bold">NaeemAli9338</span>
                                    </td>
                                    <td class="py-2">test@test.com</td>
                                    <td class="py-2">TRXUG0ZGH1XUR</td>
                                    <td class="py-2">Signup Bonus</td>
                                    <td class="py-2">
                                        <strong class="green-color">+8 USD</strong>
                                    </td>
                                    <td class="py-2">System</td>
                                    <td class="py-2">
                                        Success
                                    </td>
                                    <td class="py-2">Jan 01 2024 12:17</td>
                                </tr>
                                <tr class="even">
                                    <td class="py-2">
                                        <span class="fw-bold">NaeemMunir2507</span>
                                    </td>
                                    <td class="py-2">bo.augustin@falkcia.com</td>
                                    <td class="py-2">TRXD8LJHRWUWM</td>
                                    <td class="py-2">Signup Bonus</td>
                                    <td class="py-2">
                                        <strong class="green-color">+8 USD</strong>
                                    </td>
                                    <td class="py-2">System</td>
                                    <td class="py-2">Success</td>
                                    <td class="py-2">Jan 01 2024 11:57</td>
                                </tr>
                                <tr class="even">
                                    <td class="py-2">
                                        <span class="fw-bold">test23945</span>
                                    </td>
                                    <td class="py-2">test2@gmail.com</td>
                                    <td class="py-2">TRXFCWBHCYYJE</td>
                                    <td class="py-2">Signup Bonus</td>
                                    <td class="py-2">
                                        <strong class="green-color">+8 USD</strong>
                                    </td>
                                    <td class="py-2">System</td>
                                    <td class="py-2">Success</td>
                                    <td class="py-2">Dec 18 2023 11:32</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @include('backend.reports.__configure_modal')
@endsection