@extends('backend.layouts.app')
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
                <form action="{{ isset($bonus) ? route('admin.bonus.update', $bonus->id) : route('admin.bonus.store') }}" method="post" enctype="">
                    @csrf
                    @if (isset($bonus))
                        @method('PUT')
                    @endif

                    
                    <div class="grid grid-cols-12 gap-5">
                        <div class="col-span-12">
                            <div class="input-area">
                                <label class="form-label" for="">Bonus Name:</label>
                                <input type="text" class="form-control" name="bonus_name" id="" value="{{ old('bonus_name', $bonus->bonus_name ?? '') }}">
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
                                <label class="form-label" for="" id="bonus-type-value-label">Percentage Value:</label>
                                <input type="text" class="form-control" name="amount" id="" value="{{ old('amount', $bonus->amount ?? '') }}">
                            </div>
                            <small><span class="" style="color: red;">{{ $errors->first('amount') }}</span></small>
                        </div>
                        <div class="lg:col-span-6 col-span-12">
                            <div class="input-area">
                                <label class="form-label" for="">Process:</label>
                                <select name="process" class="form-control w-100" id="process">
                                    <option value="deposit" {{ old('process', $bonus->process ?? '') == 'deposit' ? 'selected' : '' }}>On Deposit</option>
                                    <option value="birthday" {{ old('process', $bonus->process ?? '') == 'birthday' ? 'selected' : '' }}>On Birthday</option>
                                    <option value="low_balance" {{ old('process', $bonus->process ?? '') == 'low_balance' ? 'selected' : '' }}>On Low Balance</option>
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
                        {{-- <div class="lg:col-span-6 col-span-12">
                            <div class="input-area">
                                <label class="form-label" for="">Bonus Removal:</label>
                                <select name="removal" class="form-control w-100" id="gateway-select">
                                    <option value="">In Percentage</option>
                                    <option value="">In Amount</option>
                                    <option value="">Full Bonus</option>
                                </select>
                            </div>
                            <small><span class="" style="color: red;">{{ $errors->first('start_date') }}</span></small>
                        </div> --}}
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
                                <label class="form-label" for="">KYC Verified Only:</label>
                                <div class="switch-field flex overflow-hidden same-type">
                                    <input type="radio" id="kyc_verified" name="kyc_verified" value="1" {{ old('kyc_verified', $bonus->kyc_verified ?? '1') == '1' ? 'checked' : '' }} >
                                    <label for="kyc_verified">Yes</label>
                                    <input type="radio" id="radio-six" name="kyc_verified" value="0" {{ old('kyc_verified', $bonus->kyc_verified ?? '') == '0' ? 'checked' : '' }}>
                                    <label for="radio-six">No</label>
                                </div>
                            </div>
                            <small><span class="" style="color: red;">{{ $errors->first('kyc_verified') }}</span></small>
                        </div>
                        <div class="lg:col-span-6 col-span-12">
                            <div class="input-area">
                                <label class="form-label" for="">First Deposit Only:</label>
                                <div class="switch-field flex overflow-hidden same-type">
                                    <input type="radio" id="radio-seven" name="first_deposit" value="1" {{ old('first_deposit', $bonus->first_deposit ?? '1') == '1' ? 'checked' : '' }}>
                                    <label for="radio-seven">Yes</label>
                                    <input type="radio" id="radio-eight" name="first_deposit" value="0" {{ old('first_deposit', $bonus->first_deposit ?? '') ==  '0' ? 'checked' : '' }}>
                                    <label for="radio-eight">No</label>
                                </div>
                            </div>
                            <small><span class="" style="color: red;">{{ $errors->first('first_deposit') }}</span></small>
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
<script>
    $(function(){
        $('#bonus-type').on('change', function(){
            if($(this).val() == 'percentage'){
                $('#bonus-type-value-label').text('Percentage Value: ')
            }else{
                $('#bonus-type-value-label').text('Fixed Amount Value: ')
            }
        })
    })
</script>
@endsection
