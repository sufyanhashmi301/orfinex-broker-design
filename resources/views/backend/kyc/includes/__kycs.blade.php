@if (count($kycs) != 0)
    <div class="card">
        <div class="card-body relative px-6 pt-3">
            <div class="overflow-x-auto -mx-6 dashcode-data-table">
                <span class="col-span-8 hidden"></span>
                <span class="col-span-4 hidden"></span>
                <div class="inline-block min-w-full align-middle">
                    <div class="overflow-hidden ">
                        <table class="min-w-full divide-y divide-slate-100 table-fixed dark:divide-slate-700"
                            id="dataTable" >
                            <thead>
                                <tr>
                                    <th scope="col" class="table-th">{{ __('User') }}</th>
                                    <th scope="col" class="table-th">{{ __('Method') }}</th>
                                    <th scope="col" class="table-th">{{ __('Verified at') }}</th>
                                    <th scope="col" class="table-th">{{ __('Status') }}</th>
                                    <th scope="col" class="table-th">{{ __('Action') }}</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-slate-100 dark:bg-slate-800 dark:divide-slate-700">
                                @foreach ($kycs as $kyc)
                                    
                                    <tr class="pt-1">
                                        <td scope="col" class="table-td ">
                                            @include('backend.user.components.__user_column', ['user' => $kyc->user])
                                        </td>

                                        <td scope="col" class="table-td">
                                            <span class="">{{ $kyc->method }}</span>
                                        </td>

                                        <td scope="col" class="table-td">
                                            @if ($kyc->verified_at != null)
                                                {{ $kyc->verified_at }}
                                            @else
                                                -
                                            @endif
                                        </td>

                                        <td scope="col" class="table-td">
                                            <span class="badge badge-primary" style="text-transform: capitalize">{{ str_replace('_', ' ', $kyc->status) }}</span>
                                        </td>

                                        <td scope="col" class="table-td">
                                            @can('kyc-action')
                                                @if ($kyc->data != null)
                                                    
                                                    <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#kyc-details-modal{{ $kyc->id }}" type="button" id="action-kyc" >
                                                        Details
                                                    </button>
                                                    
                                                @endif

                                                @if ($kyc->status == \App\Enums\KycStatusEnums::UNVERIFIED)
                                                    <form action="{{ route('admin.kyc.action') }}" method="post" style="display: inline">
                                                        @csrf
                                                        <input type="hidden" name="id" value="{{ $kyc->id }}">
                                                        <button name="action" value="approve" class="btn btn-primary btn-sm" type="submit" >
                                                            Approve Manually 
                                                        </button>
                                                    </form>
                                                @endif

                                                @if ($kyc->status == \App\Enums\KycStatusEnums::VERIFIED)
                                                    <form action="{{ route('admin.kyc.action') }}" method="post" style="display: inline">
                                                        @csrf
                                                        <input type="hidden" name="id" value="{{ $kyc->id }}">
                                                        <button name="action" value="reject" class="btn btn-primary btn-sm" type="submit" >
                                                            Reject Manually 
                                                        </button>
                                                    </form>
                                                @endif

                                            @endcan
                                        </td>

                                    </tr>

                                    @if ( $kyc->data != null)
                                        @include('backend.kyc.includes.__details_modal')
                                    @endif
                                 

                                @endforeach
                            </tbody>
                        </table>
                        <div class="flex flex-wrap justify-between items-center border-t border-slate-100 dark:border-slate-700 gap-3 px-4 mt-auto">
                            <div>
                                @php
                                    $from = $kycs->firstItem();
                                    $to = $kycs->lastItem();
                                    $total = $kycs->total();
                                @endphp
                                <p class="text-sm text-gray-700 py-3">
                                    Showing <span class="font-medium">{{ $from }}</span> to <span
                                        class="font-medium">{{ $to }}</span> of <span
                                        class="font-medium">{{ $total }}</span> results
                                </p>
                            </div>
                            {{ $kycs->appends(request()->except('page'))->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@else
    <div class="card basicTable_wrapper items-center justify-center py-10 px-10">
        <div class="flex items-center justify-center flex-col gap-3">
            <img src="{{ asset('frontend/images/icon/danger.png') }}" alt="">
            <p class="text-lg text-center text-slate-600 dark:text-slate-100 mb-3">
                {{ __('Nothing to see here.') }}
            </p>
        </div>
    </div>
@endif

@push('single-script')
    <script></script>
@endpush
