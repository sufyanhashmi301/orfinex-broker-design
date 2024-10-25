@extends('backend.setting.payment.withdraw.index')
@section('title')
    {{ __('Withdraw Methods') }}
@endsection
@section('page-title')
    <div class="flex justify-between flex-wrap items-center mb-6">
        <h4 class="font-medium text-xl capitalize text-slate-500 dark:text-slate-400 inline-block ltr:pr-4 rtl:pl-4 mb-1 sm:mb-0">
            @yield('title')
        </h4>
        <div class="flex sm:space-x-4 space-x-2 sm:justify-end items-center rtl:space-x-reverse">
            @isset($button)
                <a href="{{$button['route']}}" class="btn btn-sm btn-primary inline-flex items-center justify-center" type="button">
                    <iconify-icon class="text-lg ltr:mr-2 rtl:ml-2" icon="lucide:{{$button['icon']}}"></iconify-icon>
                    {{$button['name']}}
                </a>
            @endisset
        </div>
    </div>
@endsection
@section('withdraw-content')
    <div class="grid xl:grid-cols-3 md:grid-cols-2 grid-cols-1 gap-5">
        @foreach( $withdrawMethods as $method)
            @php
                $icon = $method->icon;
                if (null != $method->gateway_id && $method->icon == ''){
                    $icon = $method->gateway->logo;
                }
            @endphp
            <div class="card border hover:shadow-lg">
                <div class="card-header items-center noborder !p-4">
                    <img class="inline-block h-10" src="{{ isset($method->gateway_id) ? $method->gateway->logo : asset($icon) }}" alt="{{ $method->name }}"/>
                    <div class="dropdown relative">
                        <button class="text-xl text-center block w-full " type="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <span class="text-lg inline-flex h-6 w-6 flex-col items-center justify-center border border-slate-200 dark:border-slate-700 rounded dark:text-slate-400">
                                <iconify-icon icon="heroicons-outline:dots-vertical"></iconify-icon>
                            </span>
                        </button>
                        <ul class="dropdown-menu min-w-[120px] absolute text-sm text-slate-700 dark:text-white hidden bg-white dark:bg-slate-700 shadow z-[2] overflow-hidden list-none text-left rounded-lg mt-1 m-0 bg-clip-padding border-none lrt:origin-top-right ">
                            <li>
                                <a href="{{ route('admin.withdraw.method.edit',['type' => strtolower($type),'id' => $method->id]) }}" class="text-slate-600 dark:text-white block font-Inter font-normal px-4 py-2 hover:bg-slate-100 dark:hover:bg-slate-600 dark:hover:text-white">
                                    <iconify-icon icon="lucide:edit" class="relative top-[2px] text-lg ltr:mr-1 rtl:ml-1"></iconify-icon>
                                    {{ __('Upadte') }}
                                </a>
                            </li>
                            <li>
                                <a href="javascript:void(0);" class="delete-method text-slate-600 dark:text-white block font-Inter font-normal px-4 py-2 hover:bg-slate-100 dark:hover:bg-slate-600 dark:hover:text-white"
                                   data-id="{{ $method->id }}" data-name="{{ $method->name }}" >
                                    <iconify-icon icon="lucide:trash" class="relative top-[2px] text-lg ltr:mr-1 rtl:ml-1"></iconify-icon>
                                    {{ __('Delete') }}
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="card-body p-4 pt-2">
                    <div class="flex items-center mb-3">
                        <h4 class="text-base font-medium dark:text-white mr-1">{{ $method->name }}</h4>
                        @if($method->status)
                            <span class="badge-success text-xs text-success capitalize rounded bg-opacity-30 px-2 py-1">
                                {{ __('Activated') }}
                            </span>
                        @else
                            <span class="badge-danger text-xs text-danger capitalize rounded bg-opacity-30 px-2 py-1">
                                {{ __('Deactivated') }}
                            </span>
                        @endif
                    </div>
                    <ul class="space-y-3">
                        <li class="flex items-center justify-between text-sm">
                            <span class="text-slate-400 mr-1">{{ __('Processing Time') }}</span>
                            <span class="capitalize">{{ $method->required_time .' '. $method->required_time_format }}</span>
                        </li>
                        <li class="flex items-center justify-between text-sm">
                            <span class="text-slate-400 mr-1">{{ __('Fee') }}</span>
                            <span>{{ $method->charge }}</span>
                        </li>
                        <li class="flex items-center justify-between text-sm">
                            <span class="text-slate-400 mr-1">{{ __('Limits') }}</span>
                            <span>{{ $method->min_withdraw .' - '. $method->maximum_deposit .$currency }}</span>
                        </li>
                    </ul>
                </div>
            </div>
        @endforeach
    </div>
    <!-- Modal for Delete deleteRiskProfileTagType -->
    @include('backend.withdraw.modals.delete_method')

@endsection
@section('payment-script')
    <script>
        $(document).ready(function () {
            // Trigger delete modal
            $('.delete-method').on('click', function (e) {
                e.preventDefault();
                var id = $(this).data('id'); // Get method ID
                var name = $(this).data('name'); // Get method name

                // Set the method name in the modal
                $('.method-name').text(name);

                // Set the form action to delete the specific method
                var url = '{{ route("admin.withdraw.method.delete", ":id") }}';
                url = url.replace(':id', id);
                $('#methodDeleteForm').attr('action', url);

                // Show the delete confirmation modal
                $('#deleteMethodModal').modal('show');
            });
        });

    </script>
@endsection
