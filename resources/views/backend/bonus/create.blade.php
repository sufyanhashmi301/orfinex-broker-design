@extends('backend.setting.payment.index')
@section('title')
    {{ isset($bonus) ? __('Edit Bonus') : __('Create New Bonus') }}
@endsection
@section('payment-content')
    <div class="max-w-5xl mx-auto">
        <div class="pageTitle flex justify-between flex-wrap items-center mb-6">
            <h4 class="font-medium text-xl capitalize text-slate-500 dark:text-slate-400 inline-block ltr:pr-4 rtl:pl-4 mb-1 sm:mb-0">
                @yield('title')
            </h4>
            <div class="flex sm:space-x-4 space-x-2 sm:justify-end items-center rtl:space-x-reverse">
                <a href="{{ route('admin.bonus.index') }}" class="btn btn-dark btn-sm inline-flex items-center justify-center">
                    <iconify-icon class="text-lg ltr:mr-2 rtl:ml-2" icon="lucide:corner-down-left"></iconify-icon>
                    {{ __('Back') }}
                </a>
            </div>
        </div>
        <div class="card">
            <div class="card-body p-6">
                <form action="{{ isset($bonus) ? route('admin.bonus.update', $bonus->id) : route('admin.bonus.store') }}" method="post" id="bonus-form" enctype="">
                    @csrf
                    @if (isset($bonus))
                        @method('PUT')
                    @endif
                    <div class="grid grid-cols-12 gap-5">
                        <div class="col-span-12">
                            <div class="input-area">
                                <label class="form-label" for="">{{ __('Bonus Name:') }}</label>
                                <input type="text" class="form-control" name="bonus_name" id="" placeholder="$200 on first desposit" value="{{ old('bonus_name', $bonus->bonus_name ?? '') }}">
                                <small><span class="" style="color: red;">{{ $errors->first('bonus_name') }}</span></small>
                            </div>
                        </div>
                        <div class="lg:col-span-6 col-span-12">
                            <div class="input-area">
                                <label class="form-label" for="">{{ __('Start Date:') }}</label>
                                <input type="date" class="form-control" name="start_date" id="" value="{{ old('start_date', $bonus->start_date ?? '') }}">
                                <small><span class="" style="color: red;">{{ $errors->first('start_date') }}</span></small>
                            </div>
                        </div>
                        <div class="lg:col-span-6 col-span-12">
                            <div class="input-area">
                                <label class="form-label" for="">{{ __('Last Date:') }}</label>
                                <input type="date" class="form-control" name="last_date" id="" value="{{ old('last_date', $bonus->last_date ?? '') }}">
                                <small><span class="" style="color: red;">{{ $errors->first('last_date') }}</span></small>
                            </div>
                        </div>
                        <div class="lg:col-span-6 col-span-12">
                            <div class="input-area">
                                <label class="form-label" for="">{{ __('Type:') }}</label>
                                <select name="type" class="form-control w-100" id="bonus-type">
                                    <option value="percentage" {{ old('type', $bonus->type ?? '' ) == 'percentage' ? 'selected' : '' }}>In Percentage</option>
                                    <option value="fixed" {{ old('type', $bonus->type ?? '') == 'fixed' ? 'selected' : '' }}>In Amount</option>
                                </select>
                            </div>
                            <small><span class="" style="color: red;">{{ $errors->first('type') }}</span></small>
                        </div>
                        <div class="lg:col-span-6 col-span-12">
                            <div class="input-area">
                                <label class="form-label" for="" id="bonus-type-value-label">{{ __('Bonus Value:') }}</label>
                                <input type="text" class="form-control" name="amount" id="" value="{{ old('amount', $bonus->amount ?? '') }}">
                            </div>
                            <small><span class="" style="color: red;">{{ $errors->first('amount') }}</span></small>
                        </div>
                        <div class="lg:col-span-6 col-span-12">
                            <div class="input-area">
                                <label class="form-label" for="">{{ __('Process:') }}</label>
                                <select name="process" class="form-control w-100" id="process">
                                    <option value="deposit" {{ old('process', $bonus->process ?? '') == 'deposit' ? 'selected' : '' }}>On Deposit</option>
                                    <option value="birthday" {{ old('process', $bonus->process ?? '') == 'birthday' ? 'selected' : '' }}>On Birthday (Soon)</option>
                                    <option value="low_balance" {{ old('process', $bonus->process ?? '') == 'low_balance' ? 'selected' : '' }}>On Low Balance (Soon)</option>
                                </select>
                            </div>
                            <small><span class="" style="color: red;">{{ $errors->first('process') }}</span></small>
                        </div>
                        <div class="lg:col-span-6 col-span-12">
                            <div class="input-area">
                                <label class="form-label" for="">{{ __('Applicable by:') }}</label>
                                <select name="applicable_by" class="form-control w-100" id="applicable_by">
                                    <option value="auto" {{ old('applicable_by', $bonus->applicable_by ?? '') == 'auto' ? 'selected' : '' }}>Auto Apply</option>
                                    <option value="client" {{ old('applicable_by', $bonus->applicable_by ?? '') == 'client' ? 'selected' : '' }}>Client Apply</option>
                                </select>
                            </div>
                            <small><span class="" style="color: red;">{{ $errors->first('applicable_by') }}</span></small>
                        </div>


                        <div class="lg:col-span-6 col-span-12">
                            <div class="input-area">
                                <label class="form-label" for="">{{ __('Bonus Removal:') }}</label>
                                <select name="bonus_removal_type" class="form-control w-100" id="bonus-removal-type-value">
                                    <option value="percentage" {{ old('bonus_removal_type', $bonus->bonus_removal_type ?? '') == 'percentage' ? 'selected' : '' }}>In Percentage</option>
                                    <option value="amount" {{ old('bonus_removal_type', $bonus->bonus_removal_type ?? '') == 'amount' ? 'selected' : '' }}>In Amount</option>
                                    <option value="full_bonus" {{ old('bonus_removal_type', $bonus->bonus_removal_type ?? '') == 'full_bonus' ? 'selected' : '' }}>Full Bonus</option>
                                </select>
                            </div>
                            <small><span class="" style="color: red;">{{ $errors->first('bonus_removal') }}</span></small>
                        </div>

                        <div class="lg:col-span-6 col-span-12">
                            <div class="input-area">
                                <label class="form-label" for="" id="bonus-removal-type-value-label">{{ __('Bonus Removal Value:') }}</label>
                                <input {{ old('bonus_removal_type', $bonus->bonus_removal ?? '') == 'full_bonus' ? 'readonly' : '' }} type="text" class="form-control bonus_removal_amount" name="bonus_removal_amount"  id="" value="{{ old('bonus_removal_amount', $bonus->bonus_removal_amount ?? '') }}">
                            </div>
                            <small><span class="" style="color: red;">{{ $errors->first('bonus_removal_amount') }}</span></small>
                        </div>

                        {{-- Account Types: Currently getting forex accounts only --}}
                        <div class="lg:col-span-12 col-span-12">
                            <div class="input-area">
                                <label class="form-label" for="">{{ __('Select Forex Account Types:') }}</label>
                                <select name="forex_account_types[]" class="select2 update-disabled form-control w-100 h-9" multiple>
                                    @foreach($forex_account_types as $type)
                                        <option value="{{ $type->id }}"
                                            @if(in_array($type->id, old('forex_account_types', isset($bonus) ? $bonus->forex_schemas->pluck('id')->toArray() : []))) selected @endif
                                        >
                                            {{ $type->title }}
                                        </option>
                                    @endforeach
                                </select>

                            </div>
                            <small><span class="" style="color: red;">{!! $errors->first('forex_account_types') !!}</span></small>
                        </div>

                        <div class="col-span-12">
                            <div class="input-area">
                                <label class="form-label" for="">{{ __('Description:') }}</label>
                                <textarea name="description" class="form-control basicTinymce" rows="6">{{ old('description', $bonus->description ?? '') }}</textarea>
                            </div>
                            <small><span class="" style="color: red;">{{ $errors->first('description') }}</span></small>
                        </div>
                        <div class="col-span-12">
                            <div class="input-area">
                                <label class="form-label" for="">{{ __('Terms & Condition Link:') }}</label>
                                <input type="url" name="terms_link" class="form-control" value="{{ old('terms_link', $bonus->terms_link ?? '') }}">
                            </div>
                            <small><span class="" style="color: red;">{{ $errors->first('terms_link') }}</span></small>
                        </div>
                        <div class="lg:col-span-6 col-span-12">
                            <div class="input-area">
                                <label class="form-label" for="">{{ __('KYC Verified Upto:') }}</label>
                                <select name="kyc_slug" class="form-control w-full">
                                    @foreach ($kyc_levels as $kyc_level)
                                        <option value="{{ $kyc_level->slug }}" {{ old('kyc_slug') == $kyc_level->slug ? 'selected' : (isset($bonus) && $bonus->kyc_slug == $kyc_level->slug ? 'selected' : ($kyc_level->slug == 'level1' ? 'selected' : '')) }}>
                                            {{ $kyc_level->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <small><span class="" style="color: red;">{{ $errors->first('kyc_slug') }}</span></small>
                        </div>
                        <div class="lg:col-span-6 col-span-12">
                            <div class="input-area">
                                <label class="form-label" for="">{{ __('Deposit Terms:') }}</label>
                                <select name="first_or_every_deposit" class="form-control w-full">
                                    <option value="first" {{ old('first_or_every_deposit', $bonus->first_or_every_deposit ?? 'first') == 'first' ? 'selected' : '' }}>
                                        {{ __('On First Deposit') }}
                                    </option>
                                    <option value="every" {{ old('first_or_every_deposit', $bonus->first_or_every_deposit ?? 'every') == 'every' ? 'selected' : '' }}>
                                        {{ __('On Every Deposit') }}
                                    </option>
                                </select>
                            </div>
                            <small><span class="" style="color: red;">{{ $errors->first('first_or_every_deposit') }}</span></small>
                        </div>
                        <div class="lg:col-span-6 col-span-12">
                            <div class="input-area">
                                <div class="flex items-center space-x-7 flex-wrap">
                                    <label class="form-label !w-auto !mb-0">
                                        {{ __('Status:') }}
                                    </label>
                                    <div class="form-switch" style="line-height: 0;">
                                        <input class="form-check-input" type="hidden" value="0" name="status"/>
                                        <label class="relative inline-flex h-6 w-[46px] items-center rounded-full transition-all duration-150 cursor-pointer">
                                            <input
                                                type="checkbox"
                                                name="status"
                                                value="1"
                                                {{ old('status', $bonus->status ?? '1') == '1' ? 'checked' : '' }}
                                                class="sr-only peer"
                                            >
                                            <span class="w-11 h-6 bg-gray-200 peer-focus:outline-none ring-0 rounded-full peer dark:bg-gray-900 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-black-500"></span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <small><span class="" style="color: red;">{{ $errors->first('status') }}</span></small>
                        </div>
                        <input type="hidden" name="bonus_id" value="{{ isset($bonus) ? $bonus->id : '' }}">
                        <div class="col-span-12 text-right">
                            <button type="submit" class="btn btn-dark inline-flex items-center justify-center">
                                {{ isset($bonus) ? 'Update Bonus' : 'Save Changes' }}
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection
@section('payment-script')

@isset($bonus)
    <script>
        $('#bonus-form').on('submit', function(){
            $('.forex-account-type').removeAttr('disabled')
        })
    </script>
@endisset

<script>
    $(function(){

        $('#bonus-type').on('change', function(){
            if($(this).val() == 'percentage'){
                // $('#bonus-type-value-label').text('Percentage Value: ')
            }else{
                // $('#bonus-type-value-label').text('Fixed Amount Value: ')
            }
        })

        $('#bonus-removal-type-value').on('change', function(){
            $('.bonus_removal_amount').val('0')
            if($(this).val() == 'percentage'){
                // $('#bonus-removal-type-value-label').text('Bonus Removal Percentage Value: ')
                $('.bonus_removal_amount').prop('readonly', false)
            }

            if($(this).val() == 'full_bonus'){
                // $('#bonus-removal-type-value-label').text('Bonus Removal (Full Bonus): ')

                $('.bonus_removal_amount').prop('readonly', true)
            }

            if($(this).val() == 'amount'){
                // $('#bonus-removal-type-value-label').text('Bonus Removal Amount Value: ')
                $('.bonus_removal_amount').prop('readonly', false)
            }1
        })

        // $('.forex-account-type').on('select2:select', function(e) {
        //     // Get the selected option that triggered the event
        //     var selectedOption = e.params.data.element;
        //     var bonusActive = $(selectedOption).data('bonus-active');

        //     // Check if the bonus is active
        //     if (bonusActive == true) {
        //         // Show confirmation dialog
        //         var confirmReplace = confirm('This account type is already associated with an active bonus. Do you want to replace it?');

        //         if (!confirmReplace) {
        //             // Deselect the option if user chooses not to replace
        //             var select2Element = $(this);
        //             setTimeout(function() {
        //                 // Deselect the option using select2 method
        //                 select2Element.val(select2Element.val().filter(function(val) {
        //                     return val != selectedOption.value;
        //                 })).trigger('change');
        //             }, 0);
        //         }
        //     }
        // });

    })
</script>
@endsection
