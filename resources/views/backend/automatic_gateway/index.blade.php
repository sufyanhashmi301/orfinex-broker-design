@extends('backend.layouts.app')
@section('title')
    {{ __('Automatic Payment Gateway') }}
@endsection
@section('content')
    <div class="card">
        <div class="card-header">
            <h4 class="card-title">{{ __('Automatic Payment Gateway') }}</h4>
        </div>
        <div class="card-body p-6 pt-0">
            <div class="overflow-x-auto -mx-6">
                <div class="inline-block min-w-full align-middle">
                    <div class="overflow-hidden ">
                        <table class="min-w-full divide-y divide-slate-100 table-fixed dark:divide-slate-700">
                            <thead class="bg-slate-200 dark:bg-slate-700">
                                <tr>
                                    <th scope="col" class="table-th">{{ __('Logo') }}</th>
                                    <th scope="col" class="table-th">{{ __('Name') }}</th>
                                    <th scope="col" class="table-th">{{ __('Supported Currency') }}</th>
                                    <th scope="col" class="table-th">{{ __('Withdraw Available') }}</th>
                                    <th scope="col" class="table-th">{{ __('Status') }}</th>
                                    <th scope="col" class="table-th">{{ __('Manage') }}</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-slate-100 dark:bg-slate-800 dark:divide-slate-700">
                                @foreach($gateways as $gateway)
                                <tr>
                                    <td class="table-td">
                                        <img class="h-7" src="{{  asset($gateway->logo) }}" alt="">
                                    </td>
                                    <td class="table-td">{{ $gateway->name }}</td>
                                    <td class="table-td"> 
                                        {{ count(json_decode($gateway->supported_currencies,true)) }}
                                    </td>
                                    <td class="table-td">
                                        @if($gateway->is_withdraw != 0)
                                            <div class="badge bg-success-500 text-white capitalize"> 
                                                {{ __('Yes') }}
                                            </div>
                                        @else
                                            <div class="badge bg-danger-500 text-white capitalize">  
                                                {{ __('No') }}
                                            </div>
                                        @endif
                                    </td>
                                    <td class="table-td">
                                        @if($gateway->status == 1)
                                            <div class="badge bg-success-500 text-success-500 bg-opacity-30 capitalize"> 
                                                {{ __('Activated') }}
                                            </div>
                                        @else
                                            <div class="badge bg-danger-500 text-danger-500 bg-opacity-30 capitalize">  
                                                {{ __('Deactivated') }}
                                            </div>
                                        @endif
                                    </td>

                                    <td class="table-td">
                                        <button
                                            class="action-btn"
                                            type="button"
                                            data-bs-toggle="modal"
                                            data-bs-target="#manage-{{$gateway->id}}"
                                        >
                                            <iconify-icon icon="lucide:settings-2"></iconify-icon>
                                        </button>
                                    </td>
                                </tr>
                                <!--  Manage Modal -->
                                @include('backend.automatic_gateway.include.__manage')
                                <!-- Manage Modal End-->

                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
