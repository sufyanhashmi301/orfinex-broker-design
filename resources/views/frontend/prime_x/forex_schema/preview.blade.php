@extends('frontend::layouts.user')
@section('title')
    {{ __('Schema Preview') }}
@endsection
@section('content')
    <div class="card p-6 mb-5 space-y-3">
     @php
        $newAccountTitle = __('Open New Account');
        $requestedType = request()->get('type');
        if ($requestedType === 'real') {
            $newAccountTitle = __('Open New Real Account');
        } elseif ($requestedType === 'demo') {
            $newAccountTitle = __('Open New Demo Account');
        }
    @endphp
        <h3 class="card-title">
            {{$newAccountTitle }}
        </h3>
        @php
            $liveApproval = setting('live_account_creation','features');
            $demoApproval = setting('demo_account_creation','features');
        @endphp
        <div id="approval-alert" class="py-3 px-4 rounded-md bg-warning-500 bg-opacity-30 text-warning-900 hidden" style="background-color:#FEF3C7; color:#92400E;"></div>
        @php $requestedType = request('type'); @endphp
        <ul class="nav nav-pills flex items-center flex-wrap list-none pl-0 space-x-4" id="account-type-tabs">
            @if(($schema->real_swap_free || $schema->real_islamic) && $requestedType !== 'demo')
                <li class="nav-item">
                    <a href="javascript:;"
                       class="nav-link block font-medium font-Inter text-sm leading-tight capitalize rounded-md px-4 py-2 focus:outline-none focus:ring-0 dark:bg-slate-900 dark:text-slate-100"
                       data-type="real" id="real-tab">
                        {{ __('Real') }}
                    </a>
                </li>
            @endif

            @if(($schema->demo_swap_free || $schema->demo_islamic) && $requestedType !== 'real')
                <li class="nav-item">
                    <a href="javascript:;"
                       class="nav-link block font-medium font-Inter text-sm leading-tight capitalize rounded-md px-4 py-2 focus:outline-none focus:ring-0 dark:bg-slate-900 dark:text-slate-100"
                       data-type="demo" id="demo-tab">
                        {{ __('Demo') }}
                    </a>
                </li>
            @endif
        </ul>

        <p class="card-text" id="account-description">
            {{ __('Risk-Free Account: Trade using virtual money') }}
        </p>
    </div>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-2 gap-5 mb-5">
        <div class="h-full">
            <h4 class="text-xl text-slate-900 mb-3">
                {{ __('Account Options') }}
            </h4>
            <div class="card h-auto">
                <div class="card-body p-6">
                    <form id="create-forex-account-form" class="space-y-5" action="{{route('user.forex-account-create-now')}}" method="post"
                          enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="account_type" id="account-type" value="real">
                        <div class="input-area">
                            <label class="form-label" for="">
                                {{ __('Account Type:') }}
                            </label>
                            <input type="hidden" class="form-control py-2 h-[48px]" value="{{the_hash($schema->id)}}"
                                   aria-label="{{ __('Nickname') }}" name="schema_id" id="select-schema" aria-describedby="basic-addon1"
                                   data-is_real_islamic="{{$schema->is_real_islamic}}"
                                   data-is_demo_islamic="{{$schema->is_demo_islamic}}"
                                   data-first-min-deposit="{{$schema->first_min_deposit}}"
                                   data-leverage="{{$schema->leverage}}" required readonly>
                            <input type="text" class="form-control py-2 h-[48px]" value="{{$schema->title}}"
                                   aria-label="{{ __('Nickname') }}" aria-describedby="basic-addon1" required readonly>
                        </div>
                        <div class="input-area">
                            <div class="flex items-center space-x-5 flex-wrap">
                                <div class="form-switch ps-0" style="line-height:0;">
                                    <label
                                        class="relative inline-flex h-6 w-[46px] items-center rounded-full transition-all duration-150 cursor-pointer toggle-checkbox"
                                        data-target="#live-islamic-group">
                                        <input type="checkbox" name="is_islamic" value="1" class="sr-only peer"
                                               id="islamic-checkbox">
                                        <span
                                            class="w-11 h-6 bg-gray-200 peer-focus:outline-none ring-0 rounded-full peer dark:bg-gray-900 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-black-500"></span>
                                    </label>
                                </div>
                                <label class="form-label pt-0 !mb-0" style="width:auto">
                                    {{ __('Swap-Free Account') }}
                                </label>
                            </div>
                        </div>
                        <div class="input-area">
                            <label class="form-label" for="select-leverage">
                                {{ __('Select Leverage:') }}
                            </label>
                            <select class="form-control py-2 h-[48px]" aria-label="Default select example"
                                    id="select-leverage" name="leverage" required>
                                @foreach(explode(',', $schema->leverage) as $leverage)
                                    <option value="{{$leverage}}">{{$leverage}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="input-area">
                            <label class="form-label" for="enter-nickname">
                                {{ __('Account Nickname:') }}
                            </label>
                            <input type="text" class="form-control py-2 h-[48px]"
                                   placeholder="{{ __('Enter Nickname') }}" aria-label="{{ __('Nickname') }}"
                                   name="account_name" id="enter-nickname" aria-describedby="basic-addon1" required>
                        </div>
                        <div class="input-area">
                            <label class="form-label" for="enter-main-password">
                                {{ __('Main Password:') }}
                            </label>
                            <input type="text" class="form-control py-2 h-[48px]"
                                   placeholder="{{ __('Enter Main Password') }}" aria-label="{{ __('Main Password') }}"
                                   name="main_password" id="enter-main-password" aria-describedby="basic-addon1"
                                   required>
                            <ul>
                                <li class="text-xs font-Inter font-normal text-danger mt-2" id="length-check-main">
                                    {{ __('Use from 8 to 20 characters') }}
                                </li>
                                <li class="text-xs font-Inter font-normal text-danger mt-1" id="letters-check-main">
                                    {{ __('Use both uppercase and lowercase letters') }}
                                </li>
                                <li class="text-xs font-Inter font-normal text-danger mt-1" id="number-check-main">
                                    {{ __('At least one number') }}
                                </li>
                                <li class="text-xs font-Inter font-normal text-danger mt-1" id="special-check-main">
                                    {{ __('At least one special character(!@#$%&*():{}|<>)') }}
                                </li>
                            </ul>
                        </div>
                        <div class="mt-4">
                            <button type="submit" class="btn inline-flex justify-center btn-primary mr-3" id="create-forex-account">
                                {{ __('Create Account') }}
                            </button>
                            <a href="{{route('user.schema')}}" class="btn inline-flex justify-center btn-outline-dark">
                                {{ __('Cancel') }}
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="h-full">
            <h4 class="text-xl text-slate-900 mb-3">
                {{ __('Account Specifications and Features') }}
            </h4>
            <div class="card h-auto">
                <div class="card-header noborder">
                    <h3 class="card-title">
                        {{ __('Standard') }}
                    </h3>
                </div>
                <div class="card-body p-6 pt-0">
                    <ul class="space-y-5">
                        <li class="flex justify-between text-sm text-slate-600 dark:text-slate-100">
                            <span>{{ __('Spread from') }}</span>
                            <span id="display-spread">{{ $schema->spread }}</span>
                        </li>
                        <li class="flex justify-between text-sm text-slate-600 dark:text-slate-100">
                            <span>{{ __('Commission') }}</span>
                            <span
                                id="display-commission">{{ $schema->commission == 0 ? __('No Commission') : $schema->commission }}</span>
                        </li>
                        <li class="flex justify-between text-sm text-slate-600 dark:text-slate-100">
                            <span>{{ __('Leverage') }}</span>
                            <span id="display-leverage">{{ explode(',', $schema->leverage)[0] }}</span>
                        </li>
                        <li class="flex justify-between text-sm text-slate-600 dark:text-slate-100">
                            <span>{{ __('Initial Deposit Limit') }}</span>
                            <span id="initial-deposit">{{ $schema->first_min_deposit }}</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <div
        class="py-[18px] px-6 font-normal font-Inter text-sm rounded-md bg-slate-800 bg-opacity-[14%] text-slate-800 dark:bg-slate-500 dark:bg-opacity-[14%] dark:text-slate-100">
        <div class="flex items-center space-x-3 rtl:space-x-reverse">
            <iconify-icon class="text-xl flex-0" icon="lucide:info"></iconify-icon>
            <p class="flex-1 font-Inter">
                {{ __('Comprehensive details on our instruments and trading conditions can be found on the') }} <a
                    href="" class="text-warning-600">{{ __('Customer Contract') }}</a> {{ __('page.') }}
            </p>
        </div>
    </div>
@endsection
@section('script')
    <script>
        $(document).ready(function () {
            // Function to update the selected account type
            function updateAccountTypeBasedOnValues() {
                var realSwapFree = '{{ $schema->real_swap_free }}'; // Get from backend
                var realIslamic = '{{ $schema->real_islamic }}';   // Get from backend
                var demoSwapFree = '{{ $schema->demo_swap_free }}'; // Get from backend
                var demoIslamic = '{{ $schema->demo_islamic }}';   // Get from backend

                var selectedAccountType = 'real'; // Default to real account
                var requestedType = '{{ request('type') }}';

                // If a type was requested via query, honor it if available
                if (requestedType === 'real' && (realSwapFree || realIslamic)) {
                    selectedAccountType = 'real';
                } else if (requestedType === 'demo' && (demoSwapFree || demoIslamic)) {
                    selectedAccountType = 'demo';
                } else {
                    // Check if real or demo values are filled
                if (realSwapFree || realIslamic) {
                    selectedAccountType = 'real'; // If any real account value is present
                } else if (demoSwapFree || demoIslamic) {
                    selectedAccountType = 'demo'; // If any demo account value is present
                }

                // If both real and demo have values, pre-select real
                if ((realSwapFree || realIslamic) && (demoSwapFree || demoIslamic)) {
                    selectedAccountType = 'real';
                }
                }

                // Update the UI based on the selected account type
                $('#account-type-tabs .nav-link').removeClass('active');
                $('#' + selectedAccountType + '-tab').addClass('active');
                $('#account-type').val(selectedAccountType);
                
                // Update the description text based on account type
                updateAccountDescription(selectedAccountType);
            }

            // Function to update account description text
            function updateAccountDescription(accountType) {
                var descriptionText = '';
                if (accountType === 'demo') {
                    descriptionText = '{{ __("Risk-Free Account: Trade using virtual money") }}';
                } else {
                    descriptionText = '{{ __("Live Account: Trade with real money and real profits") }}';
                }
                $('#account-description').text(descriptionText);
            }

            function updateApprovalAlert(accountType){
                var $alert = $('#approval-alert');
                var show = false; var text = '';
                if(accountType === 'real' && {{ $liveApproval ? 'true' : 'false' }}){
                    show = true; text = '{{ __('Real accounts require admin approval. Your request will be marked as Pending until approved.') }}';
                }
                if(accountType === 'demo' && {{ $demoApproval ? 'true' : 'false' }}){
                    show = true; text = '{{ __('Demo accounts require admin approval. Your request will be marked as Pending until approved.') }}';
                }
                if(show){ $alert.removeClass('hidden').text(text); } else { $alert.addClass('hidden').text(''); }
            }

            // Call the function to update account type based on the values
            updateAccountTypeBasedOnValues();
            updateApprovalAlert($('#account-type').val());

            // Handle account type switching when clicking on tabs
            $('#account-type-tabs .nav-link').on('click', function () {
                $('#account-type-tabs .nav-link').removeClass('active');
                $(this).addClass('active');
                var accountType = $(this).data('type');
                $('#account-type').val(accountType);
                
                // Update the description text when switching tabs
                updateAccountDescription(accountType);
                updateApprovalAlert(accountType);
            });

            function updateLeverageAndDeposit(result) {
                // Assuming result contains these fields
                $('#display-commission').text(result.commission === 0 ? '{{ __('No Commission') }}' : result.commission);
                $('#display-spread').text(result.spread);
                $('#display-leverage').text(result.display_leverage);
                $('#initial-deposit').text(result.first_min_deposit);

                // Update the leverage dropdown options
                var leverageOptions = result.leverage.split(',').map(function (leverage) {
                    return `<option value="${leverage}">${leverage}</option>`;
                }).join('');
                $('#select-leverage').html(leverageOptions);
            }

            function updateIslamicCheckboxState() {
                var accountType = $('#account-type').val();
                var select_plan_id = $('#select-schema');
                var selectedOption = select_plan_id.val();
                var isRealIslamic = select_plan_id.data('is_real_islamic');
                var isDemoIslamic = select_plan_id.data('is_demo_islamic');

                var isIslamic = false;
                if (accountType === 'real' && isRealIslamic == 1) {
                    isIslamic = true;
                } else if (accountType === 'demo' && isDemoIslamic == 1) {
                    isIslamic = true;
                }
                
                if (isIslamic) {
                    $('#islamic-checkbox').closest('.input-area').show();
                } else {
                    $('#islamic-checkbox').closest('.input-area').hide();
                }
            }

            $('#account-type-tabs .nav-link').on('click', function () {
                $('#account-type-tabs .nav-link').removeClass('active');
                $(this).addClass('active');
                var accountType = $(this).data('type');
                $('#account-type').val(accountType);

                $('#islamic-checkbox').prop('checked', false);
                updateIslamicCheckboxState();
                
                // Update the description text when switching tabs
                updateAccountDescription(accountType);
                updateApprovalAlert(accountType);
            });

            {{--$("#select-schema").on('change', function (e) {--}}
            {{--    e.preventDefault();--}}
            {{--    var id = $(this).val();--}}
            {{--    var url = '{{ route("user.schema.select", ":id") }}';--}}
            {{--    url = url.replace(':id', id);--}}

            {{--    $.ajax({--}}
            {{--        url: url,--}}

            {{--        success: function (result) {--}}
            {{--            $('#first-min-amount').text(result.first_min_deposit);--}}
            {{--            updateLeverageAndDeposit(result);--}}

            {{--            $('#select-schema').data('is_real_islamic', result.is_real_islamic);--}}
            {{--            $('#select-schema').data('is_demo_islamic', result.is_demo_islamic);--}}

            {{--            $('#islamic-checkbox').prop('checked', false);--}}
            {{--            updateIslamicCheckboxState();--}}
            {{--        }--}}
            {{--    });--}}
            {{--});--}}

            $('#account-type').on('change', function () {
                updateIslamicCheckboxState();
            });

            // Initial checkbox visibility update
            updateIslamicCheckboxState();

            $("#select-leverage").on('change', function () {
                var selectedLeverage = $(this).val();
                $('#display-leverage').text(selectedLeverage); // Update the display-leverage with the selected value
            });

            $("#selectWallet").on('change', function (e) {
                $('.gatewaySelect').empty();
                $('.manual-row').empty();
                var wallet = $(this).val();
                if (wallet === 'gateway') {
                    $.get('{{ route('gateway.list') }}', function (data) {
                        $('.gatewaySelect').append(data);
                        $('select').niceSelect();
                    });
                }
            });

            $('body').on('change', '#gatewaySelect', function (e) {
                e.preventDefault();
                $('.manual-row').empty();
                var code = $(this).val();
                var url = '{{ route("user.deposit.gateway", ":code") }}';
                url = url.replace(':code', code);
                $.get(url, function (data) {
                    if (data.credentials !== undefined) {
                        $('.manual-row').append(data.credentials);
                        imagePreview();
                    }
                });

                $('#amount').on('keyup', function (e) {
                    var amount = $(this).val();
                    $('.amount').text(Number(amount));
                    $('.currency').text(currency);
                    var charge = globalData.charge_type === 'percentage' ? calPercentage(amount, globalData.charge) : globalData.charge;
                    $('.charge2').text(charge + ' ' + currency);
                    $('.total').text(Number(amount) + Number(charge) + ' ' + currency);
                });
            });

            $('#enter-main-password').on('input', function () {
                var password = $(this).val();
                checkPassword(password, 'main', 'create-forex-account');
            });

            // Prevent double submission: disable button after first valid submit
            var isCreatingAccount = false;
            $('#create-forex-account-form').on('submit', function (e) {
                if (isCreatingAccount) {
                    e.preventDefault();
                    return false;
                }
                isCreatingAccount = true;
                var $btn = $('#create-forex-account');
                $btn.prop('disabled', true).addClass('opacity-50 cursor-not-allowed');
                $btn.data('original-text', $btn.text());
                $btn.text('{{ __("Creating...") }}');
            });
        });
    </script>
@endsection
