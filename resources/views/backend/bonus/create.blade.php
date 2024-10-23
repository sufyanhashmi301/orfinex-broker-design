@extends('backend.layouts.app')
@section('title')
    {{ isset($bonus) ? __('Edit Bonus') : __('Create New Bonus') }}
@endsection
@section('content')
    <div class="max-w-5xl mx-auto">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">
                    {{ isset($bonus) ? __('Edit Bonus') : __('Create New Bonus') }}
                </h4>
                <a href="{{ route('admin.bonus.index') }}" class="btn btn-dark btn-sm inline-flex items-center justify-center">
                    <iconify-icon class="text-lg ltr:mr-2 rtl:ml-2" icon="lucide:corner-down-left"></iconify-icon>
                    {{ __('Back') }}
                </a>
            </div>
            <div class="card-body p-6">
                <form action="{{ isset($bonus) ? route('admin.bonus.update', $bonus->id) : route('admin.bonus.store') }}" method="post" id="bonus-form" enctype="">
                    @csrf
                    @if (isset($bonus))
                        @method('PUT')
                    @endif

                    
                    <div class="grid grid-cols-12 gap-5">
                        <div class="col-span-12">
                            <div class="input-area">
                                <label class="form-label" for="">Bonus Name:</label>
                                <input type="text" class="form-control" name="bonus_name" id="" placeholder="$200 on first desposit" value="{{ old('bonus_name', $bonus->bonus_name ?? '') }}">
                                <small><span class="" style="color: red;">{{ $errors->first('bonus_name') }}</span></small>
                            </div>
                        </div>
                        <div class="lg:col-span-6 col-span-12">
                            <div class="input-area">
                                <label class="form-label" for="">Start Date:</label>
                                <input type="date" class="form-control" name="start_date" id="" value="{{ old('start_date', $bonus->start_date ?? '') }}">
                                <small><span class="" style="color: red;">{{ $errors->first('start_date') }}</span></small>
                            </div>
                        </div>
                        <div class="lg:col-span-6 col-span-12">
                            <div class="input-area">
                                <label class="form-label" for="">Last Date:</label>
                                <input type="date" class="form-control" name="last_date" id="" value="{{ old('last_date', $bonus->last_date ?? '') }}">
                                <small><span class="" style="color: red;">{{ $errors->first('last_date') }}</span></small>
                            </div>
                        </div>
                        <div class="lg:col-span-6 col-span-12">
                            <div class="input-area">
                                <label class="form-label" for="">Type:</label>
                                <select name="type" class="form-control w-100" id="bonus-type">
                                    <option value="percentage" {{ old('type', $bonus->type ?? '' ) == 'percentage' ? 'selected' : '' }}>In Percentage</option>
                                    <option value="fixed" {{ old('type', $bonus->type ?? '') == 'fixed' ? 'selected' : '' }}>In Amount</option>
                                </select>
                            </div>
                            <small><span class="" style="color: red;">{{ $errors->first('type') }}</span></small>
                        </div>
                        <div class="lg:col-span-6 col-span-12">
                            <div class="input-area">
                                <label class="form-label" for="" id="bonus-type-value-label">Bonus Value:</label>
                                <input type="text" class="form-control" name="amount" id="" value="{{ old('amount', $bonus->amount ?? '') }}">
                            </div>
                            <small><span class="" style="color: red;">{{ $errors->first('amount') }}</span></small>
                        </div>
                        <div class="lg:col-span-6 col-span-12">
                            <div class="input-area">
                                <label class="form-label" for="">Process:</label>
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
                                <label class="form-label" for="">Applicable by:</label>
                                <select name="applicable_by" class="form-control w-100" id="applicable_by">
                                    <option value="auto" {{ old('applicable_by', $bonus->applicable_by ?? '') == 'auto' ? 'selected' : '' }}>Auto Apply</option>
                                    <option value="client" {{ old('applicable_by', $bonus->applicable_by ?? '') == 'client' ? 'selected' : '' }}>Client Apply</option>
                                </select>
                            </div>
                            <small><span class="" style="color: red;">{{ $errors->first('applicable_by') }}</span></small>
                        </div>


                        <div class="lg:col-span-6 col-span-12">
                            <div class="input-area">
                                <label class="form-label" for="">Bonus Removal:</label>
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
                                <label class="form-label" for="" id="bonus-removal-type-value-label">Bonus Removal Value:</label>
                                <input {{ old('bonus_removal_type', $bonus->bonus_removal ?? '') == 'full_bonus' ? 'readonly' : '' }} type="text" class="form-control bonus_removal_amount" name="bonus_removal_amount"  id="" value="{{ old('bonus_removal_amount', $bonus->bonus_removal_amount ?? '') }}">
                            </div>
                            <small><span class="" style="color: red;">{{ $errors->first('bonus_removal_amount') }}</span></small>
                        </div>

                        {{-- Account Types: Currently getting forex accounts only --}}
                        <div class="lg:col-span-12 col-span-12">
                            <div class="input-area">
                                <label class="form-label" for="">Select Forex Account Types:</label>
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
                                <label class="form-label" for="">Description:</label>
                                <textarea name="description" class="form-control" rows="6">{{ old('description', $bonus->description ?? '') }}</textarea>
                            </div>
                            <small><span class="" style="color: red;">{{ $errors->first('description') }}</span></small>
                        </div>
                        <div class="col-span-12">
                            <div class="input-area">
                                <label class="form-label" for="">Terms & Condition Link:</label>
                                <input type="url" name="terms_link" class="form-control" value="{{ old('terms_link', $bonus->terms_link ?? '') }}">
                            </div>
                            <small><span class="" style="color: red;">{{ $errors->first('terms_link') }}</span></small>
                        </div>
                        <div class="lg:col-span-6 col-span-12">
                            <div class="input-area">
                                <label class="form-label" for="">KYC Verified Upto:</label>
                                <div class="switch-field flex overflow-hidden same-type">

                                    @foreach ($kyc_levels as $kyc_level)
                                        <input type="radio" class="kyc_level" id="kyc-radio{{ $kyc_level->id }}" 
                                            {{ old('kyc_slug') == $kyc_level->slug ? 'checked' : (isset($bonus) && $bonus->kyc_slug == $kyc_level->slug ? 'checked' : ($kyc_level->slug == 'level1' ? 'checked' : '')) }}
                                            name="kyc_slug" value="{{ $kyc_level->slug }}">
                                        <label for="kyc-radio{{ $kyc_level->id }}" data-slug="{{ $kyc_level->slug }}" class="kyc_level_button">{{ $kyc_level->name }}</label>
                                    @endforeach
                               
                                </div>
                            </div>
                            <small><span class="" style="color: red;">{{ $errors->first('kyc_slug') }}</span></small>
                        </div>
                        <div class="lg:col-span-6 col-span-12">
                            <div class="input-area">
                                <label class="form-label" for="">Deposit Terms:</label>
                                <div class="switch-field flex overflow-hidden same-type">
                                    <input type="radio" id="radio-seven" name="first_or_every_deposit" value="first" {{ old('first_or_every_deposit', $bonus->first_or_every_deposit ?? 'first') == 'first' ? 'checked' : '' }}>
                                    <label for="radio-seven">On First Deposit</label>
                                    <input type="radio" id="radio-eight" name="first_or_every_deposit" value="every" {{ old('first_or_every_deposit', $bonus->first_or_every_deposit ?? '') ==  'every' ? 'checked' : '' }}>
                                    <label for="radio-eight">On Every Deposit</label>
                                </div>
                            </div>
                            <small><span class="" style="color: red;">{{ $errors->first('first_or_every_deposit') }}</span></small>
                        </div>
                        <div class="lg:col-span-6 col-span-12">
                            <div class="input-area">
                                <label class="form-label" for="">Status:</label>
                                <div class="switch-field flex overflow-hidden same-type">
                                    <input type="radio" id="radio-nine" name="status" value="1" {{ old('status', $bonus->status ?? '1') == '1' ? 'checked' : '' }} >
                                    <label for="radio-nine">Active</label>
                                    <input type="radio" id="radio-ten" name="status" value="0" {{ old('status', $bonus->status ?? '') == '0' ? 'checked' : '' }} >
                                    <label for="radio-ten">Deactivate</label>
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
@section('script')

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
