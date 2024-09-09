@extends('backend.layouts.app')
@section('title')
    {{ __('Edit Account Type') }}
@endsection
@section('content')

    <div class="flex justify-between flex-wrap items-center mb-6">
        <h4 class="font-medium text-xl capitalize text-slate-500 dark:text-slate-400 inline-block ltr:pr-4 rtl:pl-4 mb-1 sm:mb-0">
            {{ __('Edit Account Type') }}
        </h4>
        <div class="flex sm:space-x-4 space-x-2 sm:justify-end items-center rtl:space-x-reverse">
            <a href="{{ url()->previous() }}" class="btn btn-primary inline-flex items-center justify-center">
                <iconify-icon class="text-lg ltr:mr-2 rtl:ml-2" icon="lucide:corner-down-left"></iconify-icon>
                {{ __('Back') }}
            </a>
        </div>
    </div>

    <form action="{{ route('admin.accountType.update', $schema->id) }}" method="post" enctype="multipart/form-data" id="accountTypeForm">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-12 gap-5 mb-6">
            <div class="2xl:col-span-3 lg:col-span-4 col-span-12">
                <div class="card h-full">
                    <div class="card-body p-6">
                        <div class="input-area">
                            <div class="wrap-custom-file">
                                <input type="file" name="icon" id="schema-icon" accept=".gif, .jpg, .png"/>
                                <label for="schema-icon" class="file-ok" style="background-image: url({{ asset($schema->icon) }})">
                                    <img class="upload-icon" src="{{ asset('global/materials/upload.svg') }}" alt=""/>
                                    <span>{{ __('Update Avatar') }}</span>
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
                            <label class="form-label" for="">
                                {{ __('Select countries/tags where you want to show this forex scheme:') }}
                            </label>
                            <select name="country[]" class="select2 form-control w-full h-9" multiple>
                                <option value="All"
                                        @if(in_array('All', json_decode($schema->country, true))) selected @endif>
                                    {{ __('All') }}
                                </option>
                                @foreach(getCountries() as $country)
                                    <option value="{{ $country['name'] }}"
                                            @if(in_array($country['name'], json_decode($schema->country, true))) selected @endif>
                                        {{ $country['name'] }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="input-area">
                            <label class="form-label" for="">
                                {{ __('Choose the tags where you would like this account type to be shown:') }}
                            </label>
                            <select name="tags[]" class="select2 form-control w-full h-9" multiple>
                                @foreach(getRiskProfileTag() as $tag)
                                    <option value="{{ $tag->name }}"
                                            @if(in_array($tag->name, json_decode($schema->tags ?? '[]', true))) selected @endif>
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
                    <p class="card-text">{{ __('Select all specifications and limits for account types you want clients to be able to open.') }}</p>
                </div>
            </div>
            <div class="card-body p-6 pt-0">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
                    <div class="input-area">
                        <label class="form-label">{{ __('Title:') }}</label>
                        <input type="text" name="title" class="form-control" placeholder="Account Title"
                               value="{{ $schema->title }}" required/>
                    </div>
                    <div class="input-area">
                        <label class="form-label">{{ __('Account Type Badge:') }}</label>
                        <input type="text" name="badge" class="form-control" placeholder="Account Type Badge"
                               value="{{ $schema->badge }}" required/>
                    </div>
                    <div class="input-area">
                        <label class="form-label">{{ __('Priority:') }}</label>
                        <input type="text" name="priority" oninput="this.value = validateDouble(this.value)"
                               class="form-control" placeholder="Priority e.g 1,2,3.." value="{{ $schema->priority }}"
                               required/>
                    </div>
                    <div class="input-area">
                        <label class="form-label">{{ __('Account Creation Limit:') }}</label>
                        <input type="text" name="account_limit" oninput="this.value = validateDouble(this.value)"
                               class="form-control" placeholder="Account Limit" value="{{ $schema->account_limit }}"/>
                    </div>
                    <div class="input-area">
                        <label class="form-label">{{ __('Range Start (Min 6 digits):') }}</label>
                        <input type="text" name="start_range" class="form-control" placeholder="Start Range"
                               value="{{ $schema->start_range }}"/>
                    </div>
                    <div class="input-area">
                        <label class="form-label">{{ __('Range End (Min 6 digits):') }}</label>
                        <input type="text" name="end_range" class="form-control" placeholder="End Range"
                               value="{{ $schema->end_range }}"/>
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
                        <label class="form-label">{{ __('Account Type Spread:') }}</label>
                        <input type="text" name="spread" class="form-control" placeholder="Account Type Spread"
                               value="{{ $schema->spread }}" required/>
                    </div>
                    <div class="input-area">
                        <label class="form-label">{{ __('Account Type Commission:') }}</label>
                        <input type="text" name="commission" class="form-control" placeholder="Account Type Commission"
                               value="{{ $schema->commission }}" required/>
                    </div>
                    <div class="input-area">
                        <label class="form-label">{{ __('Leverage:') }}</label>
                        <input type="text" name="leverage" class="form-control" placeholder="leverage e.g 10,20,50"
                               value="{{ $schema->leverage }}" required/>
                    </div>
                    <div class="input-area">
                        <label class="form-label">{{ __('First Min Deposit:') }}</label>
                        <input type="text" name="first_min_deposit" class="form-control" placeholder="Min deposit"
                               value="{{ $schema->first_min_deposit }}"/>
                    </div>
                </div>
            </div>
        </div>

        <!-- Phases/Steps Section -->
        <h4 class="font-medium text-xl capitalize text-slate-500 dark:text-slate-400 inline-block mb-3">
            {{ __('Phases / Steps') }}
        </h4>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mb-6">
            @foreach($schema->forexSchemaPhases as $phase)
                <div class="card">
                    <div class="card-header noborder">
                        <h4 class="card-title">{{ __('Phase ') . ($loop->iteration) }}</h4>
                    </div>
                    <div class="card-body p-6 pt-3 space-y-5">
                        <input type="hidden" name="phases[{{ $loop->index }}][id]" value="{{ $phase->id }}">
                        <!-- Phase ID -->
                        <div class="input-area">
                            <label class="form-label">{{ __('Platform Group') }}</label>
                            <input type="text" name="phases[{{ $loop->index }}][group]" class="form-control"
                                   placeholder="Platform Group" value="{{ $phase->group }}"/>
                        </div>
                        <div class="input-area">
                            <div class="flex items-center space-x-7 flex-wrap">
                                <div class="primary-radio">
                                    <label class="flex items-center cursor-pointer">
                                        <input type="radio" class="hidden" name="phases[{{ $loop->index }}][type]" value="{{ \App\Enums\FundedSchemeTypes::CHALLENGE_PHASE }}" {{ $phase->type == \App\Enums\FundedSchemeTypes::CHALLENGE_PHASE ? 'checked' : '' }}>
                                        <span class="flex-none bg-white dark:bg-slate-500 rounded-full border inline-flex ltr:mr-2 rtl:ml-2 relative transition-all duration-150 h-[16px] w-[16px] border-slate-400 dark:border-slate-600 dark:ring-slate-700"></span>
                                        <span class="text-info text-sm leading-6 capitalize">
                                            {{ __('Challenge Phase') }}
                                        </span>
                                    </span>
                                    </label>
                                </div>
                                <div class="primary-radio">
                                    <label class="flex items-center cursor-pointer">
                                        <input type="radio" class="hidden" name="phases[{{ $loop->index }}][type]" value="{{ \App\Enums\FundedSchemeTypes::FUNDED_PHASE }}" {{ $phase->type == \App\Enums\FundedSchemeTypes::FUNDED_PHASE ? 'checked' : '' }}>
                                        <span class="flex-none bg-white dark:bg-slate-500 rounded-full border inline-flex ltr:mr-2 rtl:ml-2 relative transition-all duration-150 h-[16px] w-[16px] border-slate-400 dark:border-slate-600 dark:ring-slate-700"></span>
                                        <span class="text-info text-sm leading-6 capitalize">
                                            {{ __('Funded Phase') }}
                                        </span>
                                    </label>
                                </div>
                                <div class="primary-radio">
                                    <label class="flex items-center cursor-pointer">
                                        <input type="radio" class="hidden" name="phases[{{ $loop->index }}][type]" value="{{ \App\Enums\FundedSchemeTypes::DIRECT_FUNDING }}" {{ $phase->type == \App\Enums\FundedSchemeTypes::DIRECT_FUNDING ? 'checked' : '' }}>
                                        <span class="flex-none bg-white dark:bg-slate-500 rounded-full border inline-flex ltr:mr-2 rtl:ml-2 relative transition-all duration-150 h-[16px] w-[16px] border-slate-400 dark:border-slate-600 dark:ring-slate-700"></span>
                                        <span class="text-info text-sm leading-6 capitalize">
                                            {{ __('Direct Funding') }}
                                        </span>
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="input-area">
                            <label class="form-label">{{ __('Validity Period') }}</label>
                            <select name="phases[{{ $loop->index }}][validity_count]"
                                    class="select2 form-control w-full">
                                @for ($i = 1; $i <= 12; $i++)
                                    <option value="{{ $i }}" {{ $phase->validity_count == $i ? 'selected' : '' }}>
                                        {{ $i }} {{ __('Month') }}
                                    </option>
                                @endfor
                            </select>
                        </div>
                        <div class="input-area">
                            <label class="form-label">{{ __('Server: ') }}</label>
                            <select name="phases[{{ $loop->index }}][server]" class="select2 form-control w-full">
                                <option
                                    value="{{ setting('live_server', 'platform_api') }}" {{ $phase->server == setting('live_server', 'platform_api') ? 'selected' : '' }}>
                                    {{ setting('live_server', 'platform_api') }}
                                </option>
                            </select>
                        </div>
                        <div class="flex items-center gap-3">
                            <button type="button"
                                    class="btn btn-secondary light inline-flex items-center justify-center w-full"
                                    data-bs-toggle="modal" data-bs-target="#controlRoomModal"
                                    data-phase-index="{{ $loop->index }}">
                                {{ __('Control Room') }}
                            </button>
                        </div>
                    </div>
                </div>
            @endforeach
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

        <!-- More Details -->
        <h4 class="font-medium text-xl capitalize text-slate-500 dark:text-slate-400 mb-3">{{ __('More Details') }}</h4>
        <div class="card">
            <div class="card-body p-6">
                <div class="input-area mb-5">
                    <label for="desc" class="form-label">{{ __('Detail:') }}</label>
                    <textarea class="summernote" name="desc">{{ $schema->desc }}</textarea>
                </div>
                <div class="grid grid-cols-12 gap-5 items-center">
                    <div class="2xl:col-span-3 lg:col-span-4 col-span-12">
                        <div class="input-area">
                            <select name="status" class="select2 form-control w-full" data-placeholder="Status">
                                <option
                                    value="1" {{ $schema->status == 1 ? 'selected' : '' }}>{{ __('Active') }}</option>
                                <option
                                    value="0" {{ $schema->status == 0 ? 'selected' : '' }}>{{ __('Deactivate') }}</option>
                            </select>
                        </div>
                    </div>
                    <div class="2xl:col-span-9 lg:col-span-8 col-span-12">
                        <div class="grid md:grid-cols-3 col-span-1 gap-5">
                            <div class="input-area">
                                <div class="flex items-center space-x-7 flex-wrap">
                                    <div class="form-switch ps-0">
                                        <input type="hidden" name="is_weekend_holding" value="0">
                                        <label class="relative inline-flex h-6 w-[46px] items-center rounded-full transition-all duration-150 cursor-pointer">
                                            <input type="checkbox" name="is_weekend_holding" value="1" class="sr-only peer" {{ $schema->is_weekend_holding ? 'checked' : '' }}>
                                            <span class="w-11 h-6 bg-gray-200 peer-focus:outline-none ring-0 rounded-full peer dark:bg-gray-900 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-black-500"></span>
                                        </label>
                                    </div>
                                    <label class="form-label !w-auto">{{ __('Weekend Holding') }}</label>
                                </div>
                            </div>
                            <div class="input-area">
                                <div class="flex items-center space-x-7 flex-wrap">
                                    <div class="form-switch ps-0">
                                        <input type="hidden" name="is_scalable" value="0">
                                        <label class="relative inline-flex h-6 w-[46px] items-center rounded-full transition-all duration-150 cursor-pointer">
                                            <input type="checkbox" name="is_scalable" value="1" class="sr-only peer" {{ $schema->is_scalable ? 'checked' : '' }}>
                                            <span class="w-11 h-6 bg-gray-200 peer-focus:outline-none ring-0 rounded-full peer dark:bg-gray-900 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-black-500"></span>
                                        </label>
                                    </div>
                                    <label class="form-label !w-auto">{{ __('Scalable') }}</label>
                                </div>
                            </div>
                            <div class="input-area">
                                <div class="flex items-center space-x-7 flex-wrap">
                                    <div class="form-switch ps-0">
                                        <input type="hidden" name="is_refundable" value="0">
                                        <label class="relative inline-flex h-6 w-[46px] items-center rounded-full transition-all duration-150 cursor-pointer">
                                            <input type="checkbox" name="is_refundable" value="1" class="sr-only peer" {{ $schema->is_refundable ? 'checked' : '' }}>
                                            <span class="w-11 h-6 bg-gray-200 peer-focus:outline-none ring-0 rounded-full peer dark:bg-gray-900 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-black-500"></span>
                                        </label>
                                    </div>
                                    <label class="form-label !w-auto">{{ __('Refundable') }}</label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    @include('backend.forex_schema.modal.__edit_control_room', ['phases' => $schema->forexSchemaPhases])

    <!-- Update Button -->
        <div class="mt-10 flex items-center gap-3">
            <button type="submit" class="btn btn-dark inline-flex items-center justify-center">
                <iconify-icon class="text-xl ltr:mr-2 rtl:ml-2" icon="lucide:check"></iconify-icon>
                {{ __('Update') }}
            </button>
            <button type="button" class="btn btn-outline-secondary">{{ __('Cancel') }}</button>
        </div>
    </form>

    <div id="notification-container" class="fixed top-0 right-0 mt-4 mr-4 space-y-2 z-50"></div>

@endsection

@section('script')
    <script>
        $(document).ready(function () {
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

            // Function to add a new rule row for a specific phase
            function addNewRule(phaseIndex) {
                const rowCount = $('#rulesTable_' + phaseIndex + ' tbody tr').length; // Get the current number of rows
                const newRow = `<tr>
                    <input type="hidden" name="rules[${phaseIndex}][${rowCount}][id]" value="" />
                    <td class="table-td">
                    <input type="text" name="rules[${phaseIndex}][${rowCount}][unique_id]" value="" class="form-control" readonly />
                    </td>
                    <td class="table-td"><input type="text" name="rules[${phaseIndex}][${rowCount}][allotted_funds]" class="form-control validate-number" /></td>
                    <td class="table-td"><input type="text" name="rules[${phaseIndex}][${rowCount}][daily_drawdown_limit]" class="form-control validate-number" /></td>
                    <td class="table-td"><input type="text" name="rules[${phaseIndex}][${rowCount}][max_drawdown_limit]" class="form-control validate-number" /></td>
                    <td class="table-td"><input type="text" name="rules[${phaseIndex}][${rowCount}][profit_target]" class="form-control validate-number" /></td>
                    <td class="table-td"><input type="text" name="rules[${phaseIndex}][${rowCount}][fee]" class="form-control validate-number" /></td>
                    <td class="table-td"><input type="text" name="rules[${phaseIndex}][${rowCount}][discount]" class="form-control validate-number" /></td>
                    <td class="table-td">
                        <label class="relative inline-flex h-6 w-[46px] items-center rounded-full transition-all duration-150 cursor-pointer">
                            <input type="checkbox" name="rules[${phaseIndex}][${rowCount}][is_new_order]" value="1" class="sr-only peer">
                            <span class="w-11 h-6 bg-gray-200 peer-focus:outline-none ring-0 rounded-full peer dark:bg-gray-900 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-black-500"></span>
                        </label>
                    </td>
                    <td class="table-td">
                        <a href="#" class="action-btn deleteRule">
                            <iconify-icon icon="lucide:trash"></iconify-icon>
                        </a>
                    </td>
                </tr>`;
                $('#rulesTable_' + phaseIndex + ' tbody').append(newRow); // Append the new row to the specific phase's table
                }

// Add a new rule when the "New Rule" button is clicked
            $('#newRule').on('click', function () {
                const phaseIndex = $(this).closest('.modal').find('.rulesTable').attr('id').split('_')[1]; // Get the phase index dynamically
                addNewRule(phaseIndex);
            });


            // Delete rule row logic for existing and new rules
            $('.rulesTable').on('click', '.deleteRule', function (e) {
                e.preventDefault();
                $(this).closest('tr').remove(); // Remove the row when the delete button is clicked
            });

            // Initialize the modal and check if any rules exist for the phase. If not, add a default rule
            $('#controlRoomModal').on('shown.bs.modal', function (event) {
                const phaseIndex = $(event.relatedTarget).data('phase-index'); // Get the phase index from the modal trigger
                if ($('#rulesTable_' + phaseIndex + ' tbody tr').length === 0) {
                    addNewRule(phaseIndex); // Add a default rule if no rules are available
                }
            });

            // Validation function to check if all fields are filled in and numeric
            function validateRules() {
                let isValid = true;

                // Loop through all inputs with the class 'validate-number'
                $('.validate-number').each(function () {
                    const value = $(this).val();
                    if (value === '' || isNaN(value)) { // Check if the value is empty or not a number
                        $(this).addClass('border-red-500'); // Add red border for invalid fields
                        if ($(this).next('.error-message').length === 0) {
                            $(this).after('<span class="error-message text-red-500">This field is required and must be a number.</span>');
                        }
                        isValid = false;
                    } else {
                        $(this).removeClass('border-red-500');
                        $(this).next('.error-message').remove(); // Remove error message if the field is valid
                    }
                });

                return isValid;
            }

            // Validate the form when clicking the "Update Rules" button
            $('.update-rules').on('click', function (e) {
                e.preventDefault();
                if (validateRules()) {
                    $('#controlRoomModal').modal('hide'); // If validation passes, close the modal
                }
            });

            // Validate main form on submission
            $('#accountTypeForm').on('submit', function (e) {
                let formIsValid = true;

                // Validate all numeric fields in the form
                $('.validate-number').each(function () {
                    const value = $(this).val();
                    if (isNaN(value) || value === '') {
                        $(this).addClass('border-red-500');
                        formIsValid = false;
                        showNotification('Please fill in all fields correctly before updating the rules.', 'error');
                    } else {
                        $(this).removeClass('border-red-500');
                    }

                });

                if (!formIsValid) {
                    e.preventDefault();
                    showNotification('Please ensure all fields are filled in correctly.', 'error');
                }
            });
        });
    </script>



@endsection
