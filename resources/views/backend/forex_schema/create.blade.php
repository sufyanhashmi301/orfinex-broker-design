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
    <form action="{{route('admin.accountType.store')}}" method="post" enctype="multipart/form-data" id="accountTypeForm">
        @csrf
        <div class="grid grid-cols-12 gap-5 mb-6">
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
                            <label class="form-label"
                                   for="">{{ __('Select countries/tags where you want to show this forex scheme(select "All" if you have to show this scheme to whole world):') }}</label>
                            <select name="country[]" class="select2 form-control w-full h-9"
                                    placeholder="Manage Country" multiple>
                                <option value="All" {{ in_array('All', old('country', [])) ? 'selected' : '' }}>
                                    {{ __('All') }}
                                </option>
                                @foreach(getCountries() as $country)
                                    <option
                                        value="{{ $country['name'] }}" {{ in_array($country['name'], old('country', [])) ? 'selected' : '' }}>
                                        {{ $country['name'] }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="input-area">
                            <label class="form-label"
                                   for="">{{ __('Choose the tags where you would like this account type to be shown:') }}</label>
                            <select name="tags[]" class="select2 form-control w-full h-9" placeholder="Manage Tags"
                                    multiple>
                                @foreach(getRiskProfileTag() as $tag)
                                    <option
                                        value="{{ $tag->name }}" {{ in_array($tag->name, old('tags', [])) ? 'selected' : '' }}>
                                        {{ $tag->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="card mb-6">
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
                            value="{{ old('title') }}"
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
                            value="{{ old('badge') }}"
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
                            value="{{ old('priority') }}"
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
                            value="{{ old('account_limit') }}"
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
                            value="{{ old('start_range') }}"
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
                            value="{{ old('end_range') }}"
                        />
                    </div>
                </div>
            </div>
        </div>
        <h4 class="font-medium text-xl capitalize text-slate-500 dark:text-slate-400 inline-block mb-3">
            {{ __('Key Features') }}
        </h4>
        <div class="card mb-6">
            <div class="card-body p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div class="input-area">
                        <label class="form-label" for="">{{ __('Account Type Spread:') }}</label>
                        <input
                            type="text"
                            class="form-control"
                            placeholder="Account Type Spread"
                            name="spread"
                            value="{{ old('spread') }}"
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
                            value="{{ old('commission') }}"
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
                            value="{{ old('leverage') }}"
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
                            value="{{ old('first_min_deposit') }}"
                        />
                    </div>
                </div>
            </div>
        </div>

        <h4 class="font-medium text-xl capitalize text-slate-500 dark:text-slate-400 inline-block mb-3">
            {{ __('Phases / Steps') }}
        </h4>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mb-6">
            <div class="card">
                <div class="card-header noborder">
                    <h4 class="card-title">{{ __('Phase 1') }}</h4>
                </div>
                <div class="card-body p-6 pt-3 space-y-5">
                    <div class="input-area">
                        <label class="form-label" for="">{{ __('Platform Group') }}</label>
                        <input
                            type="text"
                            name="group"
                            class="form-control"
                            placeholder="Platform Group"
                            value="{{ old('group') }}"
                        />
                    </div>
                    <div class="input-area !mb-7">
                        <div class="flex items-center space-x-7 flex-wrap">
                            <div class="primary-radio">
                                <label class="flex items-center cursor-pointer">
                                    <input type="radio" class="hidden" name="type" value="{{ \App\Enums\FundedSchemeTypes::CHALLENGE_PHASE }}" {{ old('type') == \App\Enums\FundedSchemeTypes::CHALLENGE_PHASE ? 'checked' : '' }}>
                                    <span class="flex-none bg-white dark:bg-slate-500 rounded-full border inline-flex ltr:mr-2 rtl:ml-2 relative transition-all duration-150 h-[16px] w-[16px] border-slate-400 dark:border-slate-600 dark:ring-slate-700"></span>
                                    <span class="text-primary text-sm leading-6 capitalize">
                                        {{ __('Challenge Phase') }}
                                    </span>
                                </label>
                            </div>

                            <div class="primary-radio">
                                <label class="flex items-center cursor-pointer">
                                    <input type="radio" class="hidden" name="type" value="{{ \App\Enums\FundedSchemeTypes::FUNDED_PHASE }}" {{ old('type') == \App\Enums\FundedSchemeTypes::FUNDED_PHASE ? 'checked' : '' }}>
                                    <span class="flex-none bg-white dark:bg-slate-500 rounded-full border inline-flex ltr:mr-2 rtl:ml-2 relative transition-all duration-150 h-[16px] w-[16px] border-slate-400 dark:border-slate-600 dark:ring-slate-700"></span>
                                    <span class="text-secondary text-sm leading-6 capitalize">
                                        {{ __('Funded Phase') }}
                                    </span>
                                </label>
                            </div>

                            <div class="primary-radio">
                                <label class="flex items-center cursor-pointer">
                                    <input type="radio" class="hidden" name="type" value="{{ \App\Enums\FundedSchemeTypes::DIRECT_FUNDING }}" {{ old('type') == \App\Enums\FundedSchemeTypes::DIRECT_FUNDING ? 'checked' : '' }}>
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
                        <select name="validity_count" class="select2 form-control w-full">
                            @for ($i = 1; $i <= 12; $i++)
                                <option value="{{ $i }}" {{ old('validity_count') == $i ? 'selected' : '' }}>
                                    {{ $i }} {{ __('Month') }}
                                </option>
                            @endfor
                        </select>
                    </div>
                    <div class="input-area">
                        <label class="form-label" for="">{{ __('Server: ') }}</label>
                        <select name="server" class="select2 form-control w-full">
                            <option
                                value="{{ setting('live_server', 'platform_api') }}" {{ old('server') == setting('live_server', 'platform_api') ? 'selected' : '' }}>
                                {{ setting('live_server', 'platform_api') }}
                            </option>
                        </select>
                    </div>
                    <div class="flex items-center gap-3">
                        <button type="button"
                                class="btn btn-secondary light inline-flex items-center justify-center w-full"
                                data-bs-toggle="modal" data-bs-target="#controlRoomModal">
                            {{ __('Control Room') }}
                        </button>
                        <button type="button"
                                class="btn btn-secondary light inline-flex items-center justify-center w-full">
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

        <h4 class="font-medium text-xl capitalize text-slate-500 dark:text-slate-400 inline-block mb-3">
            {{ __('More Details') }}
        </h4>
        @include('backend.forex_schema.modal.__control_room')
        <div class="card">
            <div class="card-body p-6">
                <div class="input-area mb-5">
                    <label for="" class="form-label">{{ __('Detail:') }}</label>
                    <div class="site-editor">
                        <textarea class="summernote" name="desc">{{ old('desc') }}</textarea>
                    </div>
                </div>
                <div class="grid grid-cols-12 gap-5 items-center">
                    <div class="2xl:col-span-3 lg:col-span-4 col-span-12">
                        <div class="input-area">
                            <select name="status" class="select2 form-control w-full" data-placeholder="Status">
                                <option value="1" {{ old('status') == '1' ? 'selected' : '' }}>
                                    {{ __('Active') }}
                                </option>
                                <option value="0" {{ old('status') == '0' ? 'selected' : '' }}>
                                    {{ __('Deactivate') }}
                                </option>
                            </select>
                        </div>
                    </div>
                    <div class="2xl:col-span-9 lg:col-span-8 col-span-12">
                        <div class="grid md:grid-cols-3 col-span-1 gap-5">
                            <div class="input-area">
                                <div class="flex items-center space-x-7 flex-wrap">
                                    <div class="form-switch ps-0" style="line-height:0;">
                                        <input type="hidden" name="is_weekend_holding" value="0">
                                        <label
                                            class="relative inline-flex h-6 w-[46px] items-center rounded-full transition-all duration-150 cursor-pointer">
                                            <input type="checkbox" name="is_weekend_holding" value="1"
                                                   class="sr-only peer" {{ old('is_weekend_holding') ? 'checked' : '' }}>
                                            <span
                                                class="w-11 h-6 bg-gray-200 peer-focus:outline-none ring-0 rounded-full peer dark:bg-gray-900 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-black-500"></span>
                                        </label>
                                    </div>
                                    <label class="form-label !w-auto pt-0 !mb-0">{{ __('Weekend Holding') }}</label>
                                </div>
                            </div>
                            <div class="input-area">
                                <div class="flex items-center space-x-7 flex-wrap">
                                    <div class="form-switch ps-0" style="line-height:0;">
                                        <input type="hidden" name="is_scalable" value="0">
                                        <label
                                            class="relative inline-flex h-6 w-[46px] items-center rounded-full transition-all duration-150 cursor-pointer">
                                            <input type="checkbox" name="is_scalable" value="1"
                                                   class="sr-only peer" {{ old('is_scalable') ? 'checked' : '' }}>
                                            <span
                                                class="w-11 h-6 bg-gray-200 peer-focus:outline-none ring-0 rounded-full peer dark:bg-gray-900 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-black-500"></span>
                                        </label>
                                    </div>
                                    <label class="form-label !w-auto pt-0 !mb-0">{{ __('Scalable') }}</label>
                                </div>
                            </div>
                            <div class="input-area">
                                <div class="flex items-center space-x-7 flex-wrap">
                                    <div class="form-switch ps-0" style="line-height:0;">
                                        <input type="hidden" name="is_refundable" value="0">
                                        <label class="relative inline-flex h-6 w-[46px] items-center rounded-full transition-all duration-150 cursor-pointer">
                                            <input type="checkbox" name="is_refundable" value="1" class="sr-only peer" {{ old('is_refundable') ? 'checked' : '' }}>
                                            <span class="w-11 h-6 bg-gray-200 peer-focus:outline-none ring-0 rounded-full peer dark:bg-gray-900 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-black-500"></span>
                                        </label>
                                    </div>
                                    <label class="form-label !w-auto pt-0 !mb-0">{{ __('Refundable') }}</label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                {{--Modal for Control Room--}}
                <div class="mt-10 flex items-center gap-3">
                    <button type="submit" class="btn btn-dark inline-flex items-center justify-center" id="submit-form">
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
    <div id="notification-container" class="fixed top-0 right-0 mt-4 mr-4 space-y-2 z-50"></div>

@endsection
@section('script')
    <script>
        // $('body').on('submit','#accountTypeForm', function(e) {
        //     alert('Form submit triggered');
        //     e.preventDefault();
        // });
        function showNotification(message, type) {
            const container = document.getElementById('notification-container');

            // Create a new notification element
            const notification = document.createElement('div');
            notification.className = `p-4 mb-2 rounded-md text-white text-sm ${type === 'success' ? 'bg-green-500' : 'bg-red-500'}`;
            notification.textContent = message;

            // Append the notification to the container
            container.appendChild(notification);

            // Automatically remove the notification after a few seconds
            setTimeout(() => {
                notification.remove();
            }, 5000); // 5 seconds
        }

        $(document).ready(function () {
            // Initialize the first rule row when the modal is opened
            function initializeFirstRule() {
                const newRow = `<tr>
                    <td class="table-td"><input type="text" name="rules[0][allotted_funds]" class="form-control validate-number" oninput="this.value = validateDouble(this.value)" /></td>
                    <td class="table-td"><input type="text" name="rules[0][daily_drawdown_limit]" class="form-control validate-number" oninput="this.value = validateDouble(this.value)" /></td>
                    <td class="table-td"><input type="text" name="rules[0][max_drawdown_limit]" class="form-control validate-number" oninput="this.value = validateDouble(this.value)" /></td>
                    <td class="table-td"><input type="text" name="rules[0][profit_target]" class="form-control validate-number" oninput="this.value = validateDouble(this.value)" /></td>
                    <td class="table-td"><input type="text" name="rules[0][fee]" class="form-control validate-number" oninput="this.value = validateDouble(this.value)" /></td>
                    <td class="table-td"><input type="text" name="rules[0][discount]" class="form-control validate-number" oninput="this.value = validateDouble(this.value)" /></td>
                    <td class="table-td">
                        <label class="relative inline-flex h-6 w-[46px] items-center rounded-full transition-all duration-150 cursor-pointer">
                            <input type="checkbox" name="rules[0][is_new_order]" value="1" class="sr-only peer">
                            <span class="w-11 h-6 bg-gray-200 peer-focus:outline-none ring-0 rounded-full peer dark:bg-gray-900 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-black-500"></span>
                        </label>
                    </td>
                    <td class="table-td">
                        <a href="#" class="action-btn deleteRule">
                            <iconify-icon icon="lucide:trash"></iconify-icon>
                        </a>
                    </td>
                </tr>`;
                $('#rulesTable tbody').append(newRow);
            }

            // Add more rule rows when "New Rule" is clicked
            $('#newRule').click(function () {
                const rowCount = $('#rulesTable tbody tr').length;
                const newRow = `<tr>
                    <td class="table-td"><input type="text" name="rules[${rowCount}][allotted_funds]" class="form-control validate-number" oninput="this.value = validateDouble(this.value)" /></td>
                    <td class="table-td"><input type="text" name="rules[${rowCount}][daily_drawdown_limit]" class="form-control validate-number" oninput="this.value = validateDouble(this.value)" /></td>
                    <td class="table-td"><input type="text" name="rules[${rowCount}][max_drawdown_limit]" class="form-control validate-number" oninput="this.value = validateDouble(this.value)" /></td>
                    <td class="table-td"><input type="text" name="rules[${rowCount}][profit_target]" class="form-control validate-number" oninput="this.value = validateDouble(this.value)" /></td>
                    <td class="table-td"><input type="text" name="rules[${rowCount}][fee]" class="form-control validate-number" oninput="this.value = validateDouble(this.value)" /></td>
                    <td class="table-td"><input type="text" name="rules[${rowCount}][discount]" class="form-control validate-number" oninput="this.value = validateDouble(this.value)" /></td>
                    <td class="table-td">
                        <label class="relative inline-flex h-6 w-[46px] items-center rounded-full transition-all duration-150 cursor-pointer">
                            <input type="checkbox" name="rules[${rowCount}][is_new_order]" value="1" class="sr-only peer">
                            <span class="w-11 h-6 bg-gray-200 peer-focus:outline-none ring-0 rounded-full peer dark:bg-gray-900 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-black-500"></span>
                        </label>
                    </td>
                    <td class="table-td">
                        <a href="#" class="action-btn deleteRule">
                            <iconify-icon icon="lucide:trash"></iconify-icon>
                        </a>
                    </td>
                </tr>`;
                $('#rulesTable tbody').append(newRow);
            });

            // Delete row logic
            $('#rulesTable').on('click', '.deleteRule', function (e) {
                e.preventDefault();
                $(this).closest('tr').remove();
            });

            // Initialize first rule row when modal opens
            $('#controlRoomModal').on('shown.bs.modal', function () {
                if ($('#rulesTable tbody tr').length === 0) {
                    initializeFirstRule();
                }
            });

            // Validate that all fields are filled and accept only numbers
            function validateInputs() {
                let isValid = true;
                $('.validate-number').each(function () {
                    const value = $(this).val();
                    if (!value || isNaN(value)) {
                        $(this).addClass('border-red-500');
                        if ($(this).next('.error-message').length === 0) {
                            $(this).after('<span class="error-message text-red-500">Please enter a valid number.</span>');
                        }
                        isValid = false;
                    } else {
                        $(this).removeClass('border-red-500');
                        $(this).next('.error-message').remove();
                    }
                });
                return isValid;
            }

            // Ensure that at least one rule is set
            function checkForAtLeastOneRule() {
                return $('#rulesTable tbody tr').length > 0;
            }

            // Hide modal and prepare rules for submission if validation passes
            $('.update-rules').click(function (e) {
                e.preventDefault();
                if (validateInputs() && checkForAtLeastOneRule()) {
                    $('#controlRoomModal').modal('hide');
                }
            });

            //Validate main form on submission
            $('#accountTypeForm').on('submit', function (e) {
                // e.preventDefault();
                console.log('Form submit triggered');
                // console.log(validateInputs(),'validateInputs()');
                // Check if there is at least one rule
                if (!checkForAtLeastOneRule()) {
                    console.log(checkForAtLeastOneRule(), 'checkForAtLeastOneRule');
                    showNotification('At least one rule must be set on control room.', 'error');

                    e.preventDefault(); // Prevent form submission
                } else {
                    // Ensure validation passes
                    if (!validateInputs()) {
                        console.log(validateInputs(), 'validateInputs()');
                        showNotification('kindly fill out every field of rules on control room or delete the row if not needed', 'error');
                        e.preventDefault(); // Prevent form submission if validation fails
                    }
                }

                // e.preventDefault();
            });
        });

    </script>
@endsection
