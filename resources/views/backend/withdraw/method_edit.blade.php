@extends('backend.withdraw.index')
@section('title')
    {{ __('Edit Withdraw Method') }}
@endsection
@section('page-title')
    <div class="flex justify-between flex-wrap items-center mb-6">
        <h4 class="font-medium text-xl capitalize text-slate-500 dark:text-slate-400 inline-block ltr:pr-4 rtl:pl-4 mb-1 sm:mb-0">
            @yield('title')
        </h4>
    </div>
@endsection
@section('withdraw_content')
    <div class="max-w-5xl mx-auto">
        <div class="card">
            <div class="card-body p-6">
                <form action="{{ route('admin.withdraw.method.update',$withdrawMethod->id) }}" class="row"
                      method="post" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="type" value="{{ $type }}">
                    <div class="grid gird-cols-12 items-center gap-5">
                        <div class="col-span-12">
                            <div class="input-area max-w-xs">
                                <label class="form-label" for="">{{ __('Upload Icon:') }}</label>
                                <div class="wrap-custom-file">
                                    <input
                                        type="file"
                                        name="icon"
                                        id="schema-icon"
                                        accept=".gif, .jpg, .png"
                                    />
                                    <label for="schema-icon" @if($withdrawMethod->icon)  class="file-ok" style="background-image: url({{ asset($withdrawMethod->icon) }})" @endif>
                                        <img
                                            class="upload-icon"
                                            src="{{ asset('global/materials/upload.svg') }}"
                                            alt=""
                                        />
                                        <span>{{ __('Update Icon') }}</span>
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="xl:col-span-6 col-span-12">
                            <div class="input-area">
                                <label class="form-label" for="">{{ __('Name:') }}</label>
                                <input
                                    type="text"
                                    class="form-control"
                                    name="name"
                                    value="{{ $withdrawMethod->name }}"
                                />
                            </div>
                        </div>

                        @if($type == 'auto')
                            <div class="xl:col-span-6 col-span-12">
                                <div class="input-area">
                                    <label class="form-label"
                                        for="">{{ __('Gateway Supported Currency:') }}</label>
                                    <select name="currency" class="form-control w-100" id="currency">
                                        @foreach(json_decode($supported_currencies) as $currency)
                                            <option
                                                value="{{ $currency }}" @selected($currency == $withdrawMethod->currency )>{{ $currency }} </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        @endif

                        @if($type == 'manual')
                            <div class="xl:col-span-6 col-span-12">
                                <div class="input-area">
                                    <label class="form-label" for="">{{ __('Currency:') }}</label>
                                    <input
                                        type="text"
                                        class="form-control"
                                        name="currency"
                                        value="{{ $withdrawMethod->currency }}"
                                        id="currency"
                                    />
                                </div>
                            </div>
                        @endif
                        <div class="xl:col-span-6 col-span-12">
                            <div class="input-area row">
                                <div class="col-span-12">
                                    <label class="form-label" for="">{{ __('Convention Rate:') }}</label>
                                    <div class="joint-input relative">
                                        <span class="absolute left-0 top-1/2 -translate-y-1/2 w-auto h-full text-sm h-full border-r border-r-slate-200 dark:border-r-slate-700 flex items-center justify-center px-1">
                                            {{'1 '.' '. setting('site_currency','global') . ' ='}} 
                                        </span>
                                        <input type="text" name="rate" class="form-control"
                                            value="{{ $withdrawMethod->rate }}"/>
                                        <span class="absolute right-0 top-1/2 -translate-y-1/2 w-auto h-full text-sm h-full border-l border-l-slate-200 dark:border-r-slate-700 flex items-center justify-center px-1" id="currency-selected">
                                            {{ $withdrawMethod->currency }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="xl:col-span-6 col-span-12">
                            <div class="input-area position-relative">
                                <label class="form-label" for="">{{ __('Charges:') }}</label>
                                <div class="relative">
                                    <input type="text" class="form-control"
                                        oninput="this.value = validateDouble(this.value)" name="charge"
                                        value="{{ $withdrawMethod->charge }}"/>
                                    <div class="prcntcurr absolute right-1 top-1/2 -translate-y-1/2 w-auto h-full text-sm h-full border-l border-l-slate-200 dark:border-l-slate-700 py-0.5">
                                        <select name="charge_type" class="w-full h-full outline-none">
                                            <option value="percentage"
                                                    @if( $withdrawMethod->charge_type == 'percentage') selected @endif>{{ __('%') }}</option>
                                            <option value="fixed"
                                                    @if( $withdrawMethod->charge_type == 'fixed') selected @endif>{{ $currencySymbol }}</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="xl:col-span-6 col-span-12">
                            <div class="input-area">
                                <label class="form-label" for="">{{ __('Minimum Withdraw:') }}</label>
                                <div class="joint-input relative">
                                    <input type="text" name="min_withdraw" class="form-control" value="{{ $withdrawMethod->min_withdraw }}"/>
                                    <span class="absolute right-0 top-1/2 -translate-y-1/2 w-auto h-full text-sm h-full border-l border-l-slate-200 dark:border-r-slate-700 flex items-center justify-center px-1" id="currency-selected">
                                        {{ setting('site_currency','global') }}
                                    </span>
                                </div>

                            </div>
                        </div>
                        <div class="xl:col-span-6 col-span-12">
                            <div class="input-area">
                                <label class="form-label" for="">{{ __('Maximum Withdraw:') }}</label>
                                <div class="joint-input relative">
                                    <input
                                        type="text"
                                        name="max_withdraw"
                                        class="form-control"
                                        value="{{ $withdrawMethod->max_withdraw  }}"
                                    />
                                    <span class="absolute right-0 top-1/2 -translate-y-1/2 w-auto h-full text-sm h-full border-l border-l-slate-200 dark:border-r-slate-700 flex items-center justify-center px-1" id="currency-selected">
                                        {{ setting('site_currency','global') }}
                                    </span>
                                </div>
                            </div>
                        </div>
                        @if($type == 'manual')
                            <div class="xl:col-span-6 col-span-12">
                                <div class="input-area">
                                    <label class="form-label" for="">{{ __('Processing Time:') }}</label>
                                    <div class="relative">
                                        <input type="text" name="required_time" value="{{ $withdrawMethod->required_time }}" class="form-control mb-0"/>
                                        <div class="prcntcurr absolute right-1 top-1/2 -translate-y-1/2 w-auto h-full text-sm h-full border-l border-l-slate-200 dark:border-l-slate-700 py-0.5">
                                            <select name="required_time_format" class="w-full h-full outline-none">
                                                @foreach(['minute' => 'Minutes','hour' => 'Hours','day' => 'Days' ] as $key => $value)
                                                    <option
                                                        @if( $withdrawMethod->required_time_format == $key) selected
                                                        @endif value="{{ $key }}">{{ $value }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif
                        <div class="xl:col-span-6 col-span-12">
                            <div class="input-area">
                                <label class="form-label" for="">{{ __('Status:') }}</label>
                                <div class="switch-field flex overflow-hidden same-type">
                                    <input
                                        type="radio"
                                        id="radio-five"
                                        name="status"
                                        value="1"
                                        @if($withdrawMethod->status) checked @endif
                                    />
                                    <label for="radio-five">{{ __('Active') }}</label>
                                    <input
                                        type="radio"
                                        id="radio-six"
                                        name="status"
                                        value="0"
                                        @if(!$withdrawMethod->status) checked @endif
                                    />
                                    <label for="radio-six">{{ __('Deactivate') }}</label>
                                </div>
                            </div>
                        </div>

                        <div class="col-span-12">
                            <div class="input-area">
                                <label class="form-label" for="">{{ __('Select countries where you want to show this forex scheme(select "All" if you have to show this scheme to whole world):') }}</label>
                                <select id="choices-multiple-remove-button" name="country[]" placeholder="Countries" multiple>
                                    @foreach( getCountries() as $country)
                                        <option value="{{$country['name']}}"  @selected( null != $withdrawMethod->country && in_array($country['name'],json_decode($withdrawMethod->country,true)))>{{$country['name']}}</option>
                                    @endforeach
                                    <option  value="All" @selected( null != $withdrawMethod->country && in_array('All',json_decode($withdrawMethod->country,true)))>
                                        {{ __('All') }}
                                    </option>

                                </select>
                            </div>

                        </div>

                        @if($type == 'manual')
                            <div class="col-span-12">
                                <a href="javascript:void(0)" id="generate" class="btn btn-dark btn-sm inline-flex items-center justify-center mb-3">
                                    Add Field option
                                </a>
                            </div>

                            <div class="addOptions col-span-12">
                                @foreach(json_decode($withdrawMethod->fields,true) as $key => $value)
                                <div class="option-remove-row grid grid-cols-12 items-center gap-5 mb-3">
                                    <div class="xl:col-span-4 md:col-span-6 col-span-12">
                                        <div class="input-area">
                                            <input name="fields[{{$key}}][name]" class="form-control"
                                                type="text"
                                                value="{{$value['name']}}" required
                                                placeholder="Field Name">
                                        </div>
                                    </div>

                                    <div class="xl:col-span-4 md:col-span-6 col-span-12">
                                        <div class="input-area">
                                            <select name="fields[{{$key}}][type]"
                                                    class="form-control w-100">
                                                <option value="text"
                                                        @if($value['type'] == 'text') selected @endif>Input
                                                    Text
                                                </option>
                                                <option value="textarea"
                                                        @if($value['type'] == 'textarea') selected @endif>
                                                    Textarea
                                                </option>
                                                <option value="file"
                                                        @if($value['type'] == 'file') selected @endif>File
                                                    upload
                                                </option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="xl:col-span-3 md:col-span-6 col-span-12">
                                        <div class="input-area mb-0">
                                            <select name="fields[{{ $key }}][validation]"
                                                    class="form-control w-100">
                                                <option value="required"
                                                        @if($value['validation'] == 'required') selected @endif>
                                                    Required
                                                </option>
                                                <option value="nullable"
                                                        @if($value['validation'] == 'nullable') selected @endif>
                                                    Optional
                                                </option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="xl:col-span-1 md:col-span-6 col-span-12">
                                        <button class="btn-dark h-[32px] w-[32px] flex items-center justify-center rounded-full text-xl  delete-option-row delete_desc" type="button">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24">
                                                <path fill="currentColor" d="M19 6.41L17.59 5L12 10.59L6.41 5L5 6.41L10.59 12L5 17.59L6.41 19L12 13.41L17.59 19L19 17.59L13.41 12z"/>
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        @endif

                        <div class="col-span-12 text-right">
                            <button type="submit" class="btn btn-dark inline-flex items-center justify-center">
                                {{ __('Save Changes') }}
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-xl-8">
                
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
    <script>
        $("#currency").on('change', function () {
            $('#currency-selected').text(this.value);
        });

        var i = Object.keys(JSON.parse(@json($withdrawMethod->fields))).length;
        $("#generate").on('click', function () {
            ++i;
            var form = `<div class="option-remove-row grid grid-cols-12 items-center gap-5 mb-3">
                    <div class="xl:col-span-4 md:col-span-6 col-span-12">
                      <div class="input-area">
                        <input name="fields[` + i + `][name]" class="form-control" type="text" value="" required placeholder="Field Name">
                      </div>
                    </div>

                    <div class="xl:col-span-4 md:col-span-6 col-span-12">
                      <div class="input-area">
                        <select name="fields[` + i + `][type]" class="form-control w-100">
                            <option value="text">Input Text</option>
                            <option value="textarea">Textarea</option>
                            <option value="file">File upload</option>
                        </select>
                      </div>
                    </div>
                    <div class="xl:col-span-3 md:col-span-6 col-span-12">
                      <div class="input-area mb-0">
                        <select name="fields[` + i + `][validation]" class="form-control w-100">
                            <option value="required">Required</option>
                            <option value="nullable">Optional</option>
                        </select>
                      </div>
                    </div>

                    <div class="xl:col-span-1 md:col-span-6 col-span-12">
                      <button class="btn-dark h-[32px] w-[32px] flex items-center justify-center rounded-full text-xl  delete-option-row delete_desc" type="button">
                        <svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24">
                            <path fill="currentColor" d="M19 6.41L17.59 5L12 10.59L6.41 5L5 6.41L10.59 12L5 17.59L6.41 19L12 13.41L17.59 19L19 17.59L13.41 12z"/>
                        </svg>
                      </button>
                    </div>
                    </div>`;
            $('.addOptions').append(form)
        });

        $(document).on('click', '.delete_desc', function () {
            $(this).closest('.option-remove-row').remove();
        });

        $('#gateway-select').on('change', function () {
            var id = $(this).val();
            var url = '{{ route('admin.gateway.supported.currency',':id') }}';
            url = url.replace(':id', id);
            $.get(url, function ($data) {
                $('#currency').html($data.view);
            })
        })

    </script>
@endsection
