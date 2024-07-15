@extends('backend.layouts.app')
@section('title')
    {{ __('Edit Account Type') }}
@endsection
@section('style')
    <link rel="stylesheet" href="{{ asset('backend/css/choices.min.css') }}" >
@endsection
@section('content')
    <div class="max-w-5xl mx-auto">
        <div class="flex justify-between flex-wrap items-center mb-6">
            <h4 class="font-medium text-xl capitalize text-slate-500 dark:text-slate-400 inline-block ltr:pr-4 rtl:pl-4 mb-1 sm:mb-0">
                {{ __('Edit Account Type') }}
            </h4>
            <div class="flex sm:space-x-4 space-x-2 sm:justify-end items-center rtl:space-x-reverse">
                <a href="{{ url()->previous() }}" class="inline-flex items-center justify-center text-success-600">
                    <iconify-icon class="text-lg ltr:mr-2 rtl:ml-2" icon="lucide:corner-down-left"></iconify-icon>
                    {{ __('Back') }}
                </a>
            </div>
        </div>
        <div class="card">
            <div class="card-body p-6">
                <form action="{{route('admin.accountType.update',$schema->id)}}" method="post" enctype="multipart/form-data">
                    @method('PUT')
                    @csrf
                    <div class="grid grid-cols-2 gap-5">
                        <div class="col-span-2">
                            <div class="input-area max-w-xs">
                                <label class="form-label" for="">{{ __('Upload Icon:') }}</label>
                                <div class="wrap-custom-file">
                                    <input
                                        type="file"
                                        name="icon"
                                        id="schema-icon"
                                        accept=".gif, .jpg, .png"
                                    />
                                    <label for="schema-icon" class="file-ok"
                                        style="background-image: url({{ asset($schema->icon) }})">
                                        <img
                                            class="upload-icon"
                                            src="{{ asset('global/materials/upload.svg')}}"
                                            alt=""
                                        />
                                        <span>{{ __('Update Avatar') }}</span>
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="lg:col-span-1 col-span-2 schema-name">
                            <div class="input-area">
                                <label class="form-label" for="">{{ __('Title:') }}</label>
                                <input
                                    type="text"
                                    name="title"
                                    value="{{$schema->title}}"
                                    class="form-control"
                                    placeholder="Forex Account Title"
                                    required
                                />
                            </div>
                        </div>
                        <div class="lg:col-span-1 col-span-2 schema-badge">
                            <div class="input-area">
                                <label class="form-label" for="">{{ __('Schema Badge:') }}</label>
                                <input
                                    type="text"
                                    class="form-control"
                                    placeholder="Schema Badge"
                                    name="badge"
                                    value="{{$schema->badge}}"
                                />
                            </div>
                        </div>
                        <div class="lg:col-span-1 col-span-2 schema-spread">
                            <div class="input-area">
                                <label class="form-label" for="">{{ __('Schema Spread:') }}</label>
                                <input
                                    type="text"
                                    class="form-control"
                                    placeholder="Schema Spread"
                                    name="spread"
                                    value="{{$schema->spread}}"
                                />
                            </div>
                        </div>
                        <div class="lg:col-span-1 col-span-2 schema-commission">
                            <div class="input-area">
                                <label class="form-label" for="">{{ __('Schema Commission:') }}</label>
                                <input
                                    type="text"
                                    class="form-control"
                                    placeholder="Schema Commission"
                                    name="commission"
                                    value="{{$schema->commission}}"
                                />
                            </div>
                        </div>
                        <div class="lg:col-span-1 col-span-2 ">
                            <div class="input-area">
                                <label class="form-label" for="">{{ __('Leverage:') }}</label>
                                <input
                                    type="text"
                                    name="leverage"
                                    value="{{$schema->leverage}}"
                                    class="form-control"
                                    placeholder="leverage e.g 10,20,50"
                                    required
                                />
                            </div>
                        </div>
                        <div class="lg:col-span-1 col-span-2 ">
                            <div class="input-area">
                                <label class="form-label" for="">{{ __('Demo (Swap):') }}</label>
                                <input
                                    type="text"
                                    name="demo_swap_free"
                                    value="{{$schema->demo_swap_free}}"
                                    class="form-control"
                                    placeholder="Demo (Swap) Group"
                                    required
                                />
                            </div>
                        </div>
                        <div class="lg:col-span-1 col-span-2 ">
                            <div class="input-area">
                                <label class="form-label" for="">{{ __('Demo (Islamic):') }}</label>
                                <input
                                    type="text"
                                    name="demo_islamic"
                                    value="{{$schema->demo_islamic}}"
                                    class="form-control"
                                    placeholder="Demo (Islamic) Group"
                                    required
                                />
                            </div>
                        </div>
                        <div class="lg:col-span-1 col-span-2 ">
                            <div class="input-area">
                                <label class="form-label" for="">{{ __('Real (Swap):') }}</label>
                                <input
                                    type="text"
                                    name="real_swap_free"
                                    value="{{$schema->real_swap_free}}"
                                    class="form-control"
                                    placeholder="Real (Swap) Group"
                                    required
                                />
                            </div>
                        </div>
                        <div class="lg:col-span-1 col-span-2 ">
                            <div class="input-area">
                                <label class="form-label" for="">{{ __('Real (Islamic):') }}</label>
                                <input
                                    type="text"
                                    name="real_islamic"
                                    value="{{$schema->real_islamic}}"
                                    class="form-control"
                                    placeholder="Real (Islamic) Group"
                                    required
                                />
                            </div>
                        </div>
                        <div class="lg:col-span-1 col-span-2 ">
                            <div class="input-area">
                                <label class="form-label" for="">{{ __('First Min Deposit:') }}</label>
                                <input
                                    type="text"
                                    name="first_min_deposit"
                                    value="{{$schema->first_min_deposit}}"
                                    oninput="this.value = validateDouble(this.value)"
                                    class="form-control"
                                    placeholder="Min deposit"
                                />
                            </div>
                        </div>
                        <div class="lg:col-span-1 col-span-2 ">
                            <div class="input-area">
                                <label class="form-label" for="">{{ __('Priority:') }}</label>
                                <input
                                    type="text"
                                    name="priority"
                                    value="{{$schema->priority}}"
                                    oninput="this.value = validateDouble(this.value)"
                                    class="form-control"
                                    placeholder="Priority e.g 1,2,3.."

                                />
                            </div>
                        </div>
                        
                        <div class="lg:col-span-1 col-span-2">
                            <div class="site-input-groups">
                                <label class="box-input-label" for="">{{ __('Account Creation Limit:') }}</label>
                                <input
                                    type="text"
                                    name="account_limit"
                                    value="{{$schema->account_limit}}"
                                    oninput="this.value = validateDouble(this.value)"
                                    class="box-input"
                                    placeholder="Account Limit"

                                />
                            </div>
                        </div>
                        <div class="col-span-2">
                            <div class="input-area">
                                <label class="form-label" for="">{{ __('Select countries where you want to show this forex scheme(select "All" if you have to show this scheme to whole world):') }}</label>
                                <select id="choices-multiple-remove-button" name="country[]" placeholder="Countries" multiple>
                                    @foreach( getCountries() as $country)
                                        <option value="{{$country['name']}}"  @selected( null != $schema->country && in_array($country['name'],json_decode($schema->country,true)))>{{$country['name']}}</option>
                                    @endforeach
                                        <option  value="All" @selected( null != $schema->country && in_array('All',json_decode($schema->country,true)))>
                                            {{ __('All') }}
                                        </option>

                                </select>
                            </div>
                        </div>
                        <div class="col-span-2">
                            <div class="input-area fw-normal">
                                <label for="" class="form-label">{{ __('Detail:') }}</label>
                                <div class="site-editor">
                                <textarea class="summernote"
                                        name="desc">{{$schema->desc}}</textarea>
                                </div>
                            </div>
                        </div>
                        <div class="lg:col-span-1 col-span-2">
                            <div class="input-area">
                                <label class="form-label" for="">{{ __('Withdraw:') }}</label>
                                <div class="switch-field flex overflow-hidden same-type">
                                    <input
                                        type="radio"
                                        id="withdraw-yes"
                                        name="is_withdraw"
                                        checked=""
                                        value="1"
                                        @checked($schema->is_withdraw)
                                    />
                                    <label for="withdraw-yes">{{ __('Yes') }}</label>
                                    <input
                                        type="radio"
                                        id="withdraw-no"
                                        name="is_withdraw"
                                        value="0"
                                        @checked(!$schema->is_withdraw)
                                    />
                                    <label for="withdraw-no">{{ __('No') }}</label>
                                </div>
                            </div>
                        </div>
                        <div class="lg:col-span-1 col-span-2">
                            <div class="input-area">
                                <label class="form-label" for="">{{ __('IB Partner:') }}</label>
                                <div class="switch-field flex overflow-hidden same-type">
                                    <input
                                        type="radio"
                                        id="ib-partner-yes"
                                        name="is_ib_partner"
                                        checked=""
                                        value="1"
                                        @checked($schema->is_ib_partner)
                                    />
                                    <label for="ib-partner-yes">{{ __('Yes') }}</label>
                                    <input
                                        type="radio"
                                        id="ib-partner-no"
                                        name="is_ib_partner"
                                        value="0"
                                        @checked(!$schema->is_ib_partner)
                                    />
                                    <label for="ib-partner-no">{{ __('No') }}</label>
                                </div>
                            </div>
                        </div>
                        <div class="lg:col-span-1 col-span-2">
                            <div class="input-area">
                                <label class="form-label" for="">{{ __('Internal Transfer:') }}</label>
                                <div class="switch-field flex overflow-hidden same-type">
                                    <input
                                        type="radio"
                                        id="internal-transfer-yes"
                                        name="is_internal_transfer"
                                        checked=""
                                        value="1"
                                        @checked($schema->is_internal_transfer)
                                    />
                                    <label for="internal-transfer-yes">{{ __('Yes') }}</label>
                                    <input
                                        type="radio"
                                        id="internal-transfer-no"
                                        name="is_internal_transfer"
                                        value="0"
                                        @checked(!$schema->is_internal_transfer)
                                    />
                                    <label for="internal-transfer-no">{{ __('No') }}</label>
                                </div>
                            </div>
                        </div>
                        <div class="lg:col-span-1 col-span-2">
                            <div class="input-area">
                                <label class="form-label" for="">{{ __('External Transfer:') }}</label>
                                <div class="switch-field flex overflow-hidden same-type">
                                    <input
                                        type="radio"
                                        id="external-transfer-yes"
                                        name="is_external_transfer"
                                        checked=""
                                        value="1"
                                        @checked($schema->is_external_transfer)
                                    />
                                    <label for="external-transfer-yes">{{ __('Yes') }}</label>
                                    <input
                                        type="radio"
                                        id="external-transfer-no"
                                        name="is_external_transfer"
                                        value="0"
                                        @checked(!$schema->is_external_transfer)
                                    />
                                    <label for="external-transfer-no">{{ __('No') }}</label>
                                </div>
                            </div>
                        </div>
                        <div class="lg:col-span-1 col-span-2">
                            <div class="input-area">
                                <label class="form-label" for="">{{ __('Bonus:') }}</label>
                                <div class="switch-field flex overflow-hidden same-type">
                                    <input
                                        type="radio"
                                        id="bonus-yes"
                                        name="is_bonus"
                                        checked=""
                                        value="1"
                                        @checked($schema->is_bonus)
                                    />
                                    <label for="bonus-yes">{{ __('Yes') }}</label>
                                    <input
                                        type="radio"
                                        id="bonus-no"
                                        name="is_bonus"
                                        value="0"
                                        @checked(!$schema->is_bonus)
                                    />
                                    <label for="bonus-no">{{ __('No') }}</label>
                                </div>
                            </div>
                        </div>
                        <div class="lg:col-span-1 col-span-2">
                            <div class="input-area">
                                <label class="form-label" for="">{{ __('Status:') }}</label>
                                <div class="switch-field flex overflow-hidden same-type">
                                    <input
                                        type="radio"
                                        id="status-active"
                                        name="status"
                                        checked=""
                                        value="1"
                                        @checked($schema->status)
                                    />
                                    <label for="status-active">{{ __('Active') }}</label>
                                    <input
                                        type="radio"
                                        id="status-deactivate"
                                        name="status"
                                        value="0"
                                        @checked(!$schema->status)
                                    />
                                    <label for="status-deactivate">{{ __('Deactivate') }}</label>
                                </div>
                            </div>
                        </div>

                        <div class="col-span-2 text-right">
                            <button type="submit" class="btn btn-dark inline-flex items-center justify-center">
                                {{ __('Update Schema') }}
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
@section('script')

     <script src="{{ asset('backend/js/choices.min.js') }}"></script>
     <script>


        var multipleCancelButton = new Choices('#choices-multiple-remove-button', {
            removeItemButton: true,
            // maxItemCount:7,
            // searchResultLimit:7,
            // renderChoiceLimit:7
        });

    </script>
@endsection