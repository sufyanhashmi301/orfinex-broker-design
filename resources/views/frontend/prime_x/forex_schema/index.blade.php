@extends('frontend::layouts.user')
@section('title')
    {{ __('Open New Account') }}
@endsection
@section('content')
    <div class="flex justify-between flex-wrap items-center mb-5">
        <h4 class="font-medium text-xl capitalize text-slate-900 inline-block ltr:pr-4 rtl:pl-4 mb-4 sm:mb-0 flex space-x-3 rtl:space-x-reverse">
            {{ __('Start Challenge') }}
        </h4>
        <div class="flex sm:space-x-4 space-x-2 sm:justify-end items-center rtl:space-x-reverse">
            <a href="{{ route('user.forex-account-logs') }}" class="btn btn-primary inline-flex items-center justify-center">{{ __('My Accounts') }}</a>
        </div>
    </div>

    <div class="grid xl:grid-cols-3 md:grid-cols-2 grid-cols-1 gap-5 mb-10">
        @foreach($schemas as $schema)
            <div class="card relative border dark:border-slate-700">
                <div class="card-body p-6">
                    <div class="mb-5">
                        <div class="flex items-center justify-between mb-1">
                            <h4>{{ $schema->title }}</h4>
                            @if($schema->badge)
                                <p class="badge badge-primary capitalize">
                                    {{ $schema->badge }}
                                </p>
                            @endif
                        </div>
                        <p class="text-sm text-success-500 mb-2">
                            {{ __('Available in countries: ') }} {{ implode(', ', json_decode($schema->country,true)) }}
                        </p>
                        <p class="text-slate-900 dark:text-white text-sm min-h-[3.75rem]">{{ $schema->desc }}</p>
                    </div>
                    <ul class="bg-slate-50 dark:bg-dark divide-y divide-slate-100 dark:divide-slate-700 px-3 rounded">
                        <li class="flex items-center py-3">
                            <span class="flex-1 text-sm text-slate-600 dark:text-slate-300">
                                {{ __('Allotted Fund') }}
                            </span>
                            <span class="flex-1 text-right">
                                <span class="bg-opacity-20 capitalize font-semibold text-sm leading-4 px-[10px] py-[2px] rounded-full inline-block bg-success-500 text-success-500">
                                   ${{ $schema->upto_allotted_fund }}
                                </span>
                            </span>
                        </li>
                        <li class="flex items-center py-3">
                            <span class="flex-1 text-sm text-slate-600 dark:text-slate-300">
                                {{ __('Profit Target') }}
                            </span>
                            <span class="flex-1 text-sm text-right text-slate-600 dark:text-slate-300">
                               ${{ $schema->upto_profit_target }}
                            </span>
                        </li>
                        <li class="flex items-center py-3">
                            <span class="flex-1 text-sm text-slate-600 dark:text-slate-300">
                                {{ __('Daily Max Loss') }}
                            </span>
                            <span class="flex-1 text-sm text-right text-slate-600 dark:text-slate-300">
                             ${{ $schema->upto_daily_max_loss }}
                            </span>
                        </li>
                        <li class="flex items-center py-3">
                            <span class="flex-1 text-sm text-slate-600 dark:text-slate-300">
                                {{ __('Maximum Loss') }}
                            </span>
                            <span class="flex-1 text-sm text-right text-slate-600 dark:text-slate-300">
                               ${{ $schema->upto_maximum_loss }}
                            </span>
                        </li>
                    </ul>
                    <a href="{{route('user.schema.preview',$schema->id)}}" class="btn inline-flex justify-center btn-primary w-full mt-5">
                        {{ __('Start Now') }}
                    </a>
                </div>
            </div>
        @endforeach
    </div>

    <h4 class="font-medium text-xl capitalize text-slate-900 mb-5">
        {{ __('Download for any OS') }}
    </h4>
    <div class="grid xl:grid-cols-6 md:grid-cols-2 grid-cols-1 gap-3">
        <!-- BEGIN: Group Chart5 -->
        @if(setting('desktop_terminal_windows_show','platform_links',false))
            <div class="card border border-slate-100 dark:border-slate-700 rounded p-4">
                <div class="h-12 w-12 flex flex-col items-center justify-center rounded bg-slate-50 dark:bg-slate-900 text-3xl mb-4">
                    <iconify-icon class="dark:text-slate-300" icon="material-symbols:window-sharp"></iconify-icon>
                </div>
                <span class="block text-base text-slate-600 font-medium dark:text-white mb-1">
                    Metatrader 5 <br>
                    <span class="text-slate-400 text-sm font-normal">for windows</span>
                </span>
                <div class="mt-5">
                    <a href="{{setting('desktop_terminal_windows_link','platform_links','javascript:void(0);')}}" class="inline-flex items-center text-sm dark:text-slate-300" target="_blank">
                        <span class="mr-1">Download</span>
                        <iconify-icon icon="hugeicons:download-01"></iconify-icon>
                    </a>
                </div>
            </div>
        @endif
        @if(setting('desktop_terminal_mac_show','platform_links',false))
            <div class="card border border-slate-100 dark:border-slate-700 rounded p-4">
                <div class="h-12 w-12 flex flex-col items-center justify-center rounded bg-slate-50 dark:bg-slate-900 text-3xl mb-4">
                    <iconify-icon class="dark:text-slate-300" icon="fa6-brands:app-store-ios"></iconify-icon>
                </div>
                <span class="block text-base text-slate-600 font-medium dark:text-white mb-1">
                    Metatrader 5 <br>
                    <span class="text-slate-400 text-sm font-normal">for MAC</span>
                </span>
                <div class="mt-5">
                    <a href="{{setting('desktop_terminal_mac_link','platform_links','javascript:void(0);')}}" class="inline-flex items-center text-sm dark:text-slate-300" target="_blank">
                        <span class="mr-1">Download</span>
                        <iconify-icon icon="hugeicons:download-01"></iconify-icon>
                    </a>
                </div>
            </div>
        @endif
        @if(setting('mobile_application_android_show','platform_links',false))
            <div class="card border border-slate-100 dark:border-slate-700 rounded p-4">
                <div class="h-12 w-12 flex flex-col items-center justify-center rounded bg-slate-50 dark:bg-slate-900 text-3xl mb-4">
                    <iconify-icon class="dark:text-slate-300" icon="ion:logo-google-playstore"></iconify-icon>
                </div>
                <span class="block text-base text-slate-600 font-medium dark:text-white mb-1">
                    Metatrader 5 <br>
                    <span class="text-slate-400 text-sm font-normal">for Android</span>
                </span>
                <div class="mt-5">
                    <a href="{{setting('mobile_application_android_link','platform_links','javascript:void(0);')}}" class="inline-flex items-center text-sm dark:text-slate-300" target="_blank">
                        <span class="mr-1">Download</span>
                        <iconify-icon icon="hugeicons:download-01"></iconify-icon>
                    </a>
                </div>
            </div>
        @endif
        @if(setting('mobile_application_Android_APK_show','platform_links',false))
            <div class="card border border-slate-100 dark:border-slate-700 rounded p-4">
                <div class="h-12 w-12 flex flex-col items-center justify-center rounded bg-slate-50 dark:bg-slate-900 text-3xl mb-4">
                    <iconify-icon class="dark:text-slate-300" icon="material-symbols:android"></iconify-icon>
                </div>
                <span class="block text-base text-slate-600 font-medium dark:text-white mb-1">
                    Metatrader 5 <br>
                    <span class="text-slate-400 text-sm font-normal">for Android APK</span>
                </span>
                <div class="mt-5">
                    <a href="{{setting('mobile_application_Android_APK_link','platform_links','javascript:void(0);')}}" class="inline-flex items-center text-sm dark:text-slate-300" target="_blank">
                        <span class="mr-1">Download</span>
                        <iconify-icon icon="hugeicons:download-01"></iconify-icon>
                    </a>
                </div>
            </div>
        @endif
        @if(setting('mobile_application_iOS_show','platform_links',false))
            <div class="card border border-slate-100 dark:border-slate-700 rounded p-4">
                <div class="h-12 w-12 flex flex-col items-center justify-center rounded bg-slate-50 dark:bg-slate-900 text-3xl mb-4">
                    <iconify-icon class="dark:text-slate-300" icon="fa6-brands:apple"></iconify-icon>
                </div>
                <span class="block text-base text-slate-600 font-medium dark:text-white mb-1">
                    Metatrader 5 <br>
                    <span class="text-slate-400 text-sm font-normal">for IOS</span>
                </span>
                <div class="mt-5">
                    <a href="{{setting('mobile_application_iOS_link','platform_links','javascript:void(0);')}}" class="inline-flex items-center text-sm dark:text-slate-300" target="_blank">
                        <span class="mr-1">Download</span>
                        <iconify-icon icon="hugeicons:download-01"></iconify-icon>
                    </a>
                </div>
            </div>
        @endif
        @if(setting('web_terminal_show','platform_links',false))
            <div class="card border border-slate-100 dark:border-slate-700 rounded p-4">
                <div class="h-12 w-12 flex flex-col items-center justify-center rounded bg-slate-50 dark:bg-slate-900 text-3xl mb-4">
                    <iconify-icon class="dark:text-slate-300" icon="mdi:web"></iconify-icon>
                </div>
                <span class="block text-base text-slate-600 font-medium dark:text-white mb-1">
                    Metatrader 5 <br>
                    <span class="text-slate-400 text-sm font-normal">web trader</span>
                </span>
                <div class="mt-5">
                    <a href="{{setting('web_terminal_link','platform_links','javascript:void(0);')}}" class="inline-flex items-center text-sm dark:text-slate-300" target="_blank">
                        <span class="mr-1">Download</span>
                        <iconify-icon icon="hugeicons:download-01"></iconify-icon>
                    </a>
                </div>
            </div>
        @endif
        <!-- END: Group Chart5 -->
    </div>

@endsection

@section('script')
    <script>
        // jQuery for handling the account creation button
        $(document).ready(function() {
            // Handle the click event for the Create Account button
            $('.create-account-btn').on('click', function() {
                // Get the selected schema id and selected rule id
                const schemaId = $(this).data('schema-id');
                const selectedRuleId = $('#rule_id_' + schemaId).val();

                // Replace the schema id in the route with the selected rule id
                const route = "{{ route('user.deposit.amount', ':rule_id') }}".replace(':rule_id', selectedRuleId);

                // Redirect to the route with the selected rule id
                window.location.href = route;
            });
        });
    </script>
@endsection
