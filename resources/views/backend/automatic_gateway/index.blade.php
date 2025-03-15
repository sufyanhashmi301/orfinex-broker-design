@extends('backend.setting.integrations.index')
@section('title')
    {{ __('Automatic Payment Gateway') }}
@endsection
@section('integrations-content')
    <div class="pageTitle flex justify-between flex-wrap items-center mb-6">
        <h4 class="font-medium text-xl capitalize text-slate-500 dark:text-slate-400 inline-block ltr:pr-4 rtl:pl-4 mb-1 sm:mb-0">
            {{ __('Automatic Payment Gateway') }}
        </h4>
    </div>
    <div class="grid xl:grid-cols-3 md:grid-cols-2 grid-cols-1 gap-5">
        @foreach($gateways as $gateway)
            <div class="card border hover:shadow-lg">
                <div class="card-header items-center noborder !p-4">
                    <img class="inline-block h-10" src="{{ $gateway->logo }}" alt="{{ $gateway->name }}"/>
                    @can('payment-gateways-action')
                        @if(json_decode($gateway->credentials))
                            <button class="action-btn" type="button" data-bs-toggle="modal" data-bs-target="#manage-{{$gateway->id}}">
                                <iconify-icon icon="lucide:settings-2"></iconify-icon>
                            </button>
                        @else
                            <button class="action-btn lockedFeature" data-id="{{$gateway->id}}">
                                <iconify-icon icon="lucide:settings-2"></iconify-icon>
                            </button>
                        @endif
                    @endcan
                </div>
                <div class="card-body p-4 pt-2">
                    <div class="flex items-center justify-between mb-3">
                        <h4 class="text-base font-medium dark:text-white mr-1">{{ $gateway->name }}</h4>
                        @if($gateway->status == 1)
                            <span class="badge-success text-xs text-success capitalize rounded bg-opacity-30 px-2 py-1">
                                {{ __('Active') }}
                            </span>
                        @else
                            <span class="badge-danger text-xs text-danger capitalize rounded bg-opacity-30 px-2 py-1">
                                {{ __('Deactive') }}
                            </span>
                        @endif
                    </div>
                    <ul class="space-y-3">
                        <li class="flex items-center justify-between text-sm">
                            <span class="text-slate-400 mr-1">{{ __('Withdraw Available') }}</span>
                            <span class="capitalize">
                                @if($gateway->is_withdraw != 0)
                                    {{ __('Yes') }}
                                @else
                                    {{ __('No') }}
                                @endif
                            </span>
                        </li>
                        <li class="flex items-center justify-between text-sm">
                            <span class="text-slate-400 mr-1">{{ __('Supported Currency') }}</span>
                            <span>{{ count(json_decode($gateway->supported_currencies,true)) }}</span>
                        </li>
                    </ul>
                </div>
            </div>
            <!--  Manage Modal -->
            @can('payment-gateways-action')
                @include('backend.automatic_gateway.include.__manage')
            @endcan
            <!-- Manage Modal End-->

            <!-- Modal for Locked Feature -->
            <div class="modal fade fixed top-0 left-0 hidden w-full h-full outline-none overflow-x-hidden overflow-y-auto" id="lockedFeatureModal" tabindex="-1" aria-labelledby="lockedFeatureModal" aria-hidden="true">
                <div class="modal-dialog modal-lg top-1/2 !-translate-y-1/2 relative w-auto pointer-events-none">
                    <div class="modal-content border-none shadow-lg relative flex flex-col w-full pointer-events-auto bg-white dark:bg-dark bg-clip-padding rounded-md outline-none text-current">
                        <div class="modal-body popup-body">
                            <div class="popup-body-text p-8">
                                <div class="locked-feature-content"></div>
                                <div class="action-btns text-center mt-5">
                                    <a href="#" class="btn btn-danger inline-flex items-center justify-center" data-bs-dismiss="modal" aria-label="Close">
                                        <iconify-icon class="text-xl ltr:mr-2 rtl:ml-2" icon="lucide:x"></iconify-icon>
                                        {{ __('Cancel') }}
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- End Modal for Locked Feature-->
        @endforeach
    </div>
@endsection
@section('integrations-script')

    <script>

        $('.lockedFeature').on('click', function (e) {
            "user strict"
            $('.locked-feature-content').empty();

            $.ajax({
                url: '{{ route("admin.feature.locked") }}',
                method: 'GET',
                success: function (data) {
                    $('.locked-feature-content').append(data)
                    $('#lockedFeatureModal').modal('show');
                }
            })
        })
    </script>

@endsection
