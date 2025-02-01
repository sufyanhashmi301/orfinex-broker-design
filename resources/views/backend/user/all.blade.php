@extends('backend.user.index')
@section('title')
    {{ __('All Customers') }}
@endsection
@section('customers-content')
    <div class="card">
        <div class="card-body relative px-6 pt-3">
            <div class="overflow-x-auto -mx-6 dashcode-data-table">
                <span class=" col-span-8  hidden"></span>
                <span class="  col-span-4 hidden"></span>
                <div class="inline-block min-w-full align-middle">
                    <div class="overflow-hidden ">
                        <table class="min-w-full divide-y divide-slate-100 dark:divide-slate-700" id="dataTable">
                            <thead>
                                <tr>
                                    <th scope="col" class="table-th">{{ __('User') }}</th>
                                    <th scope="col" class="table-th">{{ __('Challenge Accounts') }}</th>
                                    <th scope="col" class="table-th">{{ __('Funded Accounts') }}</th>
                                    <th scope="col" class="table-th">{{ __('Trial Accounts') }}</th>
                                    <th scope="col" class="table-th">{{ __('Country') }}</th>
                                    <th scope="col" class="table-th">{{ __('KYC') }}</th>
                                    <th scope="col" class="table-th">{{ __('Status') }}</th>
                                    <th scope="col" class="table-th">{{ __('Action') }}</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-slate-100 dark:bg-slate-800 dark:divide-slate-700">

                                @forelse ($users as $user)
                                    <tr>
                                        <td scope="col" class="table-td">
                                            <a href="https://localhost/prop-project/clinic/user/8/edit" class="flex">
                                                <span class="w-8 h-8 rounded-[100%] bg-slate-100 text-slate-900 dark:bg-slate-600 dark:text-slate-200 flex flex-col items-center justify-center font-normal capitalize ltr:mr-3 rtl:ml-3">
                                                    @if(null != $user->avatar)
                                                        <img  class="w-full h-full rounded-[100%] object-cover" src="{{ asset($avatar)}}" alt="">
                                                    @else
                                                        {{ $user->first_name[0] }}{{ $user->last_name[0] }}
                                                    @endif
                                                </span>
                                                <div>
                                                    <span class="text-sm text-slate-900 dark:text-white block capitalize">
                                                        {{ $user->first_name }} {{ $user->last_name }}
                                                    </span>
                                                    <span class="text-xs text-slate-500 dark:text-slate-300">
                                                        {{ $user->email }}
                                                    </span>
                                                </div>
                                            </a>
                                        </td>
                                        <td scope="col" class="table-td">
                                            @php
                                                $total_challenge_accounts = \App\Models\AccountTypeInvestment::where('user_id', $user->id)->whereHas('accountTypePhaseRule.accountTypePhase.accountType', function ($query) {
                                                    $query->where('type', \App\Enums\AccountType::CHALLENGE);
                                                })->count();
                                            @endphp     
                                            {{ $total_challenge_accounts }}
                                        </td>
                                        <td scope="col" class="table-td">
                                            @php
                                                $total_funded_accounts = \App\Models\AccountTypeInvestment::where('user_id', $user->id)->whereHas('accountTypePhaseRule.accountTypePhase.accountType', function ($query) {
                                                    $query->where('type', \App\Enums\AccountType::FUNDED);
                                                })->count();
                                            @endphp
                                            {{ $total_funded_accounts }}
                                        </td>
                                        <td scope="col" class="table-td">
                                            @php
                                                $total_trial_accounts = \App\Models\AccountTypeInvestment::where('user_id', $user->id)->where('is_trial', 1)->count();
                                            @endphp

                                            {{ $total_trial_accounts }}
                                        </td>
                                        <td scope="col" class="table-td">{{ $user->country }}</td>
                                        <td scope="col" class="table-td">
                                            @if($user->kyc == 1)
                                                <span class="text-success-500" data-bs-toggle="tooltip" data-bs-title="Verified">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="1.75rem" height="1.75rem" viewBox="0 0 24 24">
                                                        <path fill="currentColor" fill-rule="evenodd" d="M12 1.25C6.063 1.25 1.25 6.063 1.25 12S6.063 22.75 12 22.75S22.75 17.937 22.75 12S17.937 1.25 12 1.25M7.53 11.97a.75.75 0 0 0-1.06 1.06l3 3a.75.75 0 0 0 1.06 0l7-7a.75.75 0 0 0-1.06-1.06L10 14.44z" clip-rule="evenodd"/>
                                                    </svg>
                                                </span>
                                            @else
                                                <span class="text-danger-500" data-bs-toggle="tooltip" data-bs-title="Unverified">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="1.75rem" height="1.75rem" viewBox="0 0 24 24">
                                                        <path fill="currentColor" fill-rule="evenodd" d="M12 1.25C6.063 1.25 1.25 6.063 1.25 12S6.063 22.75 12 22.75S22.75 17.937 22.75 12S17.937 1.25 12 1.25M9.702 8.641a.75.75 0 0 0-1.061 1.06L10.939 12l-2.298 2.298a.75.75 0 0 0 1.06 1.06L12 13.062l2.298 2.298a.75.75 0 0 0 1.06-1.06L13.06 12l2.298-2.298a.75.75 0 1 0-1.06-1.06L12 10.938z" clip-rule="evenodd"/>
                                                    </svg>
                                                </span>
                                            @endif

                                        </td>
                                        <td scope="col" class="table-td">
                                            @if($user->status == 1)
                                                <span class="text-success-500" data-bs-toggle="tooltip" data-bs-title="Active">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="1.75rem" height="1.75rem" viewBox="0 0 24 24">
                                                        <path fill="currentColor" fill-rule="evenodd" d="M12 1.25C6.063 1.25 1.25 6.063 1.25 12S6.063 22.75 12 22.75S22.75 17.937 22.75 12S17.937 1.25 12 1.25M7.53 11.97a.75.75 0 0 0-1.06 1.06l3 3a.75.75 0 0 0 1.06 0l7-7a.75.75 0 0 0-1.06-1.06L10 14.44z" clip-rule="evenodd"/>
                                                    </svg>
                                                </span>
                                            @else
                                                <span class="text-danger-500" data-bs-toggle="tooltip" data-bs-title="DeActivated">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="1.75rem" height="1.75rem" viewBox="0 0 24 24">
                                                        <path fill="currentColor" fill-rule="evenodd" d="M12 1.25C6.063 1.25 1.25 6.063 1.25 12S6.063 22.75 12 22.75S22.75 17.937 22.75 12S17.937 1.25 12 1.25M9.702 8.641a.75.75 0 0 0-1.061 1.06L10.939 12l-2.298 2.298a.75.75 0 0 0 1.06 1.06L12 13.062l2.298 2.298a.75.75 0 0 0 1.06-1.06L13.06 12l2.298-2.298a.75.75 0 1 0-1.06-1.06L12 10.938z" clip-rule="evenodd"/>
                                                    </svg>
                                                </span>
                                            @endif
                                        </td>
                                        <td scope="col" class="table-td">
                                            <div class="flex space-x-3 rtl:space-x-reverse">
                                                @canany(['customer-basic-manage', 'customer-balance-add-or-subtract', 'customer-change-password',
                                                    'all-type-status'])
                                                    <a href="{{ route('admin.user.edit', $user->id) }}" class="toolTip onTop action-btn" data-tippy-theme="dark"
                                                        data-tippy-content="Edit User">
                                                        <iconify-icon icon="lucide:edit-3"></iconify-icon>
                                                    </a>
                                                @endcanany
                                                @can('customer-mail-send')
                                                    <span type="button" data-id="{{ $user->id }}" data-name="{{ $user->first_name . ' ' . $user->last_name }}" class="send-mail">
                                                        <button class="toolTip onTop action-btn" data-tippy-theme="dark" data-tippy-content="Send Email">
                                                            <iconify-icon icon="lucide:mail"></iconify-icon>
                                                        </button>
                                                    </span>
                                                @endcan
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td scope="col" class="table-td" colspan="8">
                                            <center style="text-transform: none">No data available.</center>
                                        </td>
                                    </tr>
                                @endforelse

                                
                            </tbody>
                        </table>
                        <div class="flex flex-wrap justify-between items-center border-t border-slate-100 dark:border-slate-700 gap-3 px-4 mt-auto">
                            <div>
                                @php
                                    $from = $users->firstItem();
                                    $to = $users->lastItem();
                                    $total = $users->total();
                                @endphp
                                <p class="text-sm text-gray-700 py-3">
                                    Showing <span class="font-medium">{{ $from }}</span> to <span class="font-medium">{{ $to }}</span> of <span class="font-medium">{{ $total }}</span> results
                                </p>
                            </div>
                            {{ $users->appends(request()->except('page'))->links() }}
                        </div>
                    </div>
                </div>
            </div>
       
        </div>
    </div>
    <!-- Modal for Send Email -->
    @can('customer-mail-send')
        @include('backend.user.modals.__mail_send')
    @endcan

@endsection

@section('customers-script')
    <script>
        $(document).ajaxComplete(function () {
            "use strict";
            $('.toolTip').tooltip({
                "html": true,
            });
        });

        $('body').on('click', '.send-mail', function () {
            var id = $(this).data('id');
            var name = $(this).data('name');
            $('#name').html(name);
            $('#userId').val(id);
            $('#sendEmail').modal('toggle')
        });
    </script>
@endsection
