@extends('backend.layouts.app')
@section('title')
    {{ __('Add New Account Type') }}
@endsection
@section('content')
    <div class="flex justify-between flex-wrap items-center mb-6">
        <h4 class="font-medium text-xl capitalize text-slate-500 dark:text-slate-400 inline-block ltr:pr-4 rtl:pl-4 mb-1 sm:mb-0">
            {{ __('Add New Account Type') }}
        </h4>
        <div class="flex sm:space-x-4 space-x-2 sm:justify-end items-center rtl:space-x-reverse">
            <a href="{{ url()->previous() }}" class="btn btn-primary inline-flex items-center justify-center">
                <iconify-icon class="text-lg ltr:mr-2 rtl:ml-2" icon="lucide:corner-down-left"></iconify-icon>
                {{ __('Back') }}
            </a>
        </div>
    </div>
    <form action="{{route('admin.accountType.store')}}" method="post" enctype="multipart/form-data" class="space-y-5">
        @csrf
        <div class="grid grid-cols-12 gap-5">
            <div class="2xl:col-span-3 lg:col-span-4 col-span-12">
                <div class="card h-full">
                    <div class="card-body p-6">
                        <div class="input-area">
                            <div class="wrap-custom-file">
                                <input
                                    type="file"
                                    name="icon"
                                    id="schema-icon"
                                    accept=".gif, .jpg, .png"
                                />
                                <label for="schema-icon">
                                    <img
                                        class="upload-icon"
                                        src="{{asset('global/materials/upload.svg')}}"
                                        alt=""
                                    />
                                    <span>{{ __('Upload Avatar') }}</span>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="2xl:col-span-9 lg:col-span-8 col-span-12">
                <div class="card h-full">
                    <div class="card-body p-6 space-y-5">
                        <div class="input-area">
                            <label class="form-label" for="">{{ __('Select countries/tags where you want to show this forex scheme(select "All" if you have to show this scheme to whole world):') }}</label>
                            <select name="country[]" class="select2 form-control w-full h-9" placeholder="Manage Country" multiple>
                                <option  value="All" >
                                    {{ __('All') }}
                                </option>
                                @foreach( getCountries() as $country)
                                    <option  value="{{ $country['name'] }}" class="inline-block font-Inter font-normal text-sm text-slate-600">
                                        {{ $country['name']  }}
                                    </option>
                                @endforeach

                            </select>
                        </div>
                        <div class="input-area">
                            <label class="form-label" for="">{{ __('Choose the tags where you would like this account type to be shown:') }}</label>
                            <select name="tags[]" class="select2 form-control w-full h-9" placeholder="Manage Tags" multiple>
                                @foreach( getRiskProfileTag() as $tag)
                                    <option  value="{{ $tag->name }}" class="inline-block font-Inter font-normal text-sm text-slate-600">
                                        {{  $tag->name  }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-header noborder">
                <div>
                    <h4 class="card-title">
                        {{ __('Type of Account') }}
                    </h4>
                    <p class="card-text">
                        {{ __('Select all specifications and limits for account types you want clients to be able to open.') }}
                    </p>
                </div>
            </div>
            <div class="card-body p-6 pt-0">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
                    <div class="input-area">
                        <label class="form-label" for="">{{ __('Title:') }}</label>
                        <input
                            type="text"
                            name="title"
                            class="form-control"
                            placeholder="Account Title"
                            required
                        />
                    </div>
                    <div class="input-area">
                        <label class="form-label" for="">{{ __('Account Type Badge:') }}</label>
                        <input
                            type="text"
                            class="form-control"
                            placeholder="Account Type Badge"
                            name="badge"
                            required
                        />
                    </div>
                    <div class="input-area">
                        <label class="form-label" for="">{{ __('Priority:') }}</label>
                        <input
                            type="text"
                            name="priority"
                            oninput="this.value = validateDouble(this.value)"
                            class="form-control"
                            placeholder="Priority e.g 1,2,3.."
                            required
                        />
                    </div>
                    <div class="input-area">
                        <label class="form-label" for="">{{ __('Account Creation Limit:') }}</label>
                        <input
                            type="text"
                            name="account_limit"
                            oninput="this.value = validateDouble(this.value)"
                            class="form-control"
                            placeholder="Account Limit"

                        />
                    </div>
                    <div class="input-area">
                        <label class="form-label" for="">{{ __('Range Start(Min 6 digits):') }}</label>
                        <input
                            type="text"
                            name="start_range"
                            oninput="this.value = validateDouble(this.value)"
                            class="form-control"
                            placeholder="Start Range"

                        />
                    </div>
                    <div class="input-area">
                        <label class="form-label" for="">{{ __('Range End(Min 6 digits):') }}</label>
                        <input
                            type="text"
                            name="end_range"
                            oninput="this.value = validateDouble(this.value)"
                            class="form-control"
                            placeholder="End Range"

                        />
                    </div>
                </div>
            </div>
        </div>
        <h4 class="font-medium text-xl capitalize text-slate-500 dark:text-slate-400 inline-block ltr:pr-4 rtl:pl-4 mb-1 sm:mb-0">
            {{ __('Key Features') }}
        </h4>
        <div class="card">
            <div class="card-body p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div class="input-area">
                        <label class="form-label" for="">{{ __('Account Type Spread:') }}</label>
                        <input
                            type="text"
                            class="form-control"
                            placeholder="Account Type Spread"
                            name="spread"
                            required
                        />
                    </div>
                    <div class="input-area">
                        <label class="form-label" for="">{{ __('Account Type Commission:') }}</label>
                        <input
                            type="text"
                            class="form-control"
                            placeholder="Account Type Commission"
                            name="commission"
                            required
                        />
                    </div>
                    <div class="input-area">
                        <label class="form-label" for="">{{ __('Leverage:') }}</label>
                        <input
                            type="text"
                            name="leverage"
                            class="form-control"
                            placeholder="leverage e.g 10,20,50"
                            required
                        />
                    </div>
                    <div class="input-area">
                        <label class="form-label" for="">{{ __('First Min Deposit:') }}</label>
                        <input
                            type="text"
                            name="first_min_deposit"
                            class="form-control"
                            placeholder="Min deposit"

                        />
                    </div>
                </div>
            </div>
        </div>

        <h4 class="font-medium text-xl capitalize text-slate-500 dark:text-slate-400 inline-block ltr:pr-4 rtl:pl-4 mb-5">
            {{ __('Phases / Steps') }}
        </h4>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
            <div class="card">
                <div class="card-header noborder">
                    <h4 class="card-title">{{ __('Phase 1') }}</h4>
                    <button class="text-xl text-center block" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <span class="text-lg inline-flex h-6 w-6 flex-col items-center justify-center border border-slate-200 dark:border-slate-700 rounded dark:text-slate-400">
                            <iconify-icon icon="heroicons-outline:dots-vertical"></iconify-icon>
                        </span>
                    </button>
                </div>
                <div class="card-body p-6 pt-3 space-y-5">
                    <div class="input-area">
                        <label class="form-label" for="">{{ __('Platform Group') }}</label>
                        <input
                            type="text"
                            name="real_swap_free"
                            class="form-control"
                            placeholder="Platform Group"
                        />
                    </div>
                    <div class="input-area !mb-7">
                        <div class="flex items-center space-x-7 flex-wrap">
                            <div class="primary-radio">
                                <label class="flex items-center cursor-pointer">
                                    <input type="radio" class="hidden" name="phase" checked>
                                    <span class="flex-none bg-white dark:bg-slate-500 rounded-full border inline-flex ltr:mr-2 rtl:ml-2 relative transition-all duration-150 h-[16px] w-[16px] border-slate-400 dark:border-slate-600 dark:ring-slate-700"></span>
                                    <span class="text-primary text-sm leading-6 capitalize">
                                        {{ __('Challenge Phase') }}
                                    </span>
                                </label>
                            </div>

                            <div class="primary-radio">
                                <label class="flex items-center cursor-pointer">
                                    <input type="radio" class="hidden" name="phase">
                                    <span class="flex-none bg-white dark:bg-slate-500 rounded-full border inline-flex ltr:mr-2 rtl:ml-2 relative transition-all duration-150 h-[16px] w-[16px] border-slate-400 dark:border-slate-600 dark:ring-slate-700"></span>
                                    <span class="text-secondary text-sm leading-6 capitalize">
                                        {{ __('Funded Phase') }}
                                    </span>
                                </label>
                            </div>

                            <div class="primary-radio">
                                <label class="flex items-center cursor-pointer">
                                    <input type="radio" class="hidden" name="phase">
                                    <span class="flex-none bg-white dark:bg-slate-500 rounded-full border inline-flex ltr:mr-2 rtl:ml-2 relative transition-all duration-150 h-[16px] w-[16px] border-slate-400 dark:border-slate-600 dark:ring-slate-700"></span>
                                    <span class="text-info text-sm leading-6 capitalize">
                                        {{ __('Direct Funding') }}
                                    </span>
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="input-area">
                        <label class="form-label" for="">{{ __('Validity Period') }}</label>
                        <select name="" class="select2 form-control w-full">
                            <option value="1 Month">{{ __('1 Month') }}</option>
                            <option value="2 Month">{{ __('2 Month') }}</option>
                            <option value="3 Month">{{ __('3 Month') }}</option>
                        </select>
                    </div>
                    <div class="input-area">
                        <label class="form-label" for="">{{ __('Server: ') }}</label>
                        <select name="" class="select2 form-control w-full">
                            <option value="brokeret">{{ __('Brokeret') }}</option>
                        </select>
                    </div>
                    <div class="flex items-center gap-3">
                        <button type="button" class="btn btn-secondary light inline-flex items-center justify-center w-full" data-bs-toggle="modal" data-bs-target="#controlRoomModal">
                            {{ __('Control Room') }}
                        </button>
                        <button type="button" class="btn btn-secondary light inline-flex items-center justify-center w-full">
                            {{ __('Statistics') }}
                        </button>
                    </div>
                </div>
            </div>
            <a href="" class="card">
                <div class="card-body h-full p-6">
                    <div class="h-full flex items-center justify-center">
                        <span class="flex flex-col items-center justify-center">
                            <iconify-icon class="text-3xl font-light mb-1" icon="tabler:layout-grid-add"></iconify-icon>
                            {{ __('Add Another Phase') }}
                        </span>
                    </div>
                </div>
            </a>
        </div>

        <h4 class="font-medium text-xl capitalize text-slate-500 dark:text-slate-400 inline-block ltr:pr-4 rtl:pl-4 mb-1 sm:mb-0">
            {{ __('More Details') }}
        </h4>
        <div class="card">
            <div class="card-body p-6">
                <div class="input-area mb-5">
                    <label for="" class="form-label">{{ __('Detail:') }}</label>
                    <div class="site-editor">
                        <textarea class="summernote" name="desc"></textarea>
                    </div>
                </div>
                <div class="grid grid-cols-12 gap-5 items-center">
                    <div class="2xl:col-span-3 lg:col-span-4 col-span-12">
                        <div class="input-area">
                            <select name="status" id="" class="select2 form-control w-full" data-placeholder="Status">
                                <option value="1">{{ __('Active') }}</option>
                                <option value="0">{{ __('Deactivate') }}</option>
                            </select>
                        </div>
                    </div>
                    <div class="2xl:col-span-9 lg:col-span-8 col-span-12">
                        <div class="grid md:grid-cols-3 col-span-1 gap-5">
                            <div class="input-area">
                                <div class="flex items-center space-x-7 flex-wrap">
                                    <div class="form-switch ps-0" style="line-height:0;">
                                        <input
                                            class="form-check-input"
                                            type="hidden"
                                            value="0"
                                            name="is_withdraw"
                                        >
                                        <label class="relative inline-flex h-6 w-[46px] items-center rounded-full transition-all duration-150 cursor-pointer">
                                            <input
                                                type="checkbox"
                                                name="is_withdraw"
                                                value="1"
                                                class="sr-only peer"
                                            >
                                            <span class="w-11 h-6 bg-gray-200 peer-focus:outline-none ring-0 rounded-full peer dark:bg-gray-900 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-black-500"></span>
                                        </label>
                                    </div>

                                    <label class="form-label !w-auto pt-0 !mb-0">
                                        {{ __('Weekend Holding') }}
                                    </label>
                                </div>
                            </div>
                            <div class="input-area">
                                <div class="flex items-center space-x-7 flex-wrap">
                                    <div class="form-switch ps-0" style="line-height:0;">
                                        <input
                                            class="form-check-input"
                                            type="hidden"
                                            value="0"
                                            name="is_internal_transfer"
                                        >
                                        <label class="relative inline-flex h-6 w-[46px] items-center rounded-full transition-all duration-150 cursor-pointer">
                                            <input
                                                type="checkbox"
                                                name="is_internal_transfer"
                                                value="1"
                                                class="sr-only peer"
                                            >
                                            <span class="w-11 h-6 bg-gray-200 peer-focus:outline-none ring-0 rounded-full peer dark:bg-gray-900 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-black-500"></span>
                                        </label>
                                    </div>
                                    <label class="form-label !w-auto pt-0 !mb-0">
                                        {{ __('Scaleable') }}
                                    </label>
                                </div>
                            </div>
                            <div class="input-area">
                                <div class="flex items-center space-x-7 flex-wrap">
                                    <div class="form-switch ps-0" style="line-height:0;">
                                        <input
                                            class="form-check-input"
                                            type="hidden"
                                            value="0"
                                            name="is_external_transfer"
                                        >
                                        <label class="relative inline-flex h-6 w-[46px] items-center rounded-full transition-all duration-150 cursor-pointer">
                                            <input
                                                type="checkbox"
                                                name="is_external_transfer"
                                                value="1"
                                                class="sr-only peer"
                                            >
                                            <span class="w-11 h-6 bg-gray-200 peer-focus:outline-none ring-0 rounded-full peer dark:bg-gray-900 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-black-500"></span>
                                        </label>
                                    </div>
                                    <label class="form-label !w-auto pt-0 !mb-0">
                                        {{ __('Refundable') }}
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="mt-10 flex items-center gap-3">
                    <button type="submit" class="btn btn-dark inline-flex items-center justify-center">
                        <iconify-icon class="text-xl ltr:mr-2 rtl:ml-2" icon="lucide:check"></iconify-icon>
                        {{ __('Add New') }}
                    </button>
                    <button type="button" class="btn btn-outline-secondary inline-flex items-center justify-center">
                        {{ __('Cancel') }}
                    </button>
                </div>
            </div>
        </div>
    </form>

    {{--Modal for Control Room--}}
    @include('backend.forex_schema.modal.__control_room')

@endsection
@section('script')
    <script>
        $(document).ready(function() {
            $('.toggle-checkbox').change(function() {
                var target = $(this).data('target');
                $(target).toggleClass('hidden');
            });

            // Add a new row to the table inside the modal
            $('#newRule').click(function() {
                const newRow = $(`
                    <tr>
                        <td class="table-td">
                            <input type="text" name="" class="form-control !py-1 !text-xs" />
                        </td>
                        <td class="table-td">
                            <input type="text" name="" class="form-control !py-1 !text-xs" />
                        </td>
                        <td class="table-td">
                            <input type="text" name="" class="form-control !py-1 !text-xs" />
                        </td>
                        <td class="table-td">
                            <input type="text" name="" class="form-control !py-1 !text-xs" />
                        </td>
                        <td class="table-td">
                            <input type="text" name="" class="form-control !py-1 !text-xs" />
                        </td>
                        <td class="table-td">
                            <input type="text" name="" class="form-control !py-1 !text-xs" />
                        </td>
                        <td class="table-td">
                            <input type="text" name="" class="form-control !py-1 !text-xs" />
                        </td>
                        <td class="table-td">
                            <div class="form-switch ps-0">
                                <input class="form-check-input" type="hidden" value="0" name="new_order">
                                <label class="relative inline-flex h-6 w-[46px] items-center rounded-full transition-all duration-150 cursor-pointer">
                                    <input type="checkbox" name="new_order" value="1" class="sr-only peer">
                                    <span class="w-11 h-6 bg-gray-200 peer-focus:outline-none ring-0 rounded-full peer dark:bg-gray-900 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-black-500"></span>
                                </label>
                            </div>
                        </td>
                        <td class="table-td">
                            <a href="#" class="action-btn deleteRule">
                                <iconify-icon icon="lucide:trash"></iconify-icon>
                            </a>
                        </td>
                    </tr>
                `);

                // Append the new row to the table body
                $('#rulesTable tbody').append(newRow);
            });

            // Delegate delete button click event
            $('#rulesTable').on('click', '.deleteRule', function(e) {
                e.preventDefault(); // Prevent the default link behavior
                // Remove the row that contains the clicked delete button
                $(this).closest('tr').remove();
            });
        });
    </script>
@endsection
