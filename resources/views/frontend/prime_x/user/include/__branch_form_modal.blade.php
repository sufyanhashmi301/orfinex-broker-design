@php
    $fields = is_array($branchFormToPrompt->fields ?? []) ? $branchFormToPrompt->fields : (is_string($branchFormToPrompt->fields ?? '') ? json_decode($branchFormToPrompt->fields, true) : []);
    $fields = $fields ?: [];
@endphp

<div class="modal fade fixed top-0 left-0 hidden w-full h-full outline-none overflow-x-hidden overflow-y-auto" id="branchFormModal" tabindex="-1" aria-labelledby="branchFormModalLabel" aria-hidden="true">
    <div class="modal-dialog top-1/2 !-translate-y-1/2 relative w-auto pointer-events-none">
        <div class="modal-content border-none shadow-lg relative flex flex-col w-full pointer-events-auto bg-white dark:bg-dark bg-clip-padding rounded-md outline-none text-current">
            <div class="modal-body px-6 py-6">
                <div class="text-center mb-4">
                    <div class="info-icon h-16 w-16 rounded-full inline-flex items-center justify-center bg-primary text-primary bg-opacity-30 mb-4" style="--tw-bg-opacity: 0.3;">
                        <iconify-icon class="text-4xl" icon="lucide:list-checks"></iconify-icon>
                    </div>
                    <h4 class="text-xl font-medium dark:text-white capitalize">{{ __('Complete Additional Details') }}</h4>
                    <p class="text-sm text-slate-500 dark:text-slate-300 mt-1">{{ __('Please provide a few details to help us set up your branch preferences.') }}</p>
                </div>
                <form action="{{ route('user.branch-form.submit') }}" method="post" enctype="multipart/form-data" id="branchFormSubmit">
                    @csrf
                    <div class="space-y-4">
                        @foreach ($fields as $field)
                            <div class="input-area">
                                <label class="form-label">{{ $field['name'] }}</label>
                                @if (($field['type'] ?? '') === 'text')
                                    <input type="text" class="form-control" name="branch_form[{{ $field['name'] }}]" @if(($field['validation'] ?? '')==='required') required @endif>
                                @elseif(($field['type'] ?? '') === 'date')
                                    <input type="text" class="form-control flatpickr-branch-date" name="branch_form[{{ $field['name'] }}]" placeholder="YYYY-MM-DD" readonly @if(($field['validation'] ?? '')==='required') required @endif>
                                @elseif(($field['type'] ?? '') === 'checkbox')
                                    <div class="col-span-12">
                                        @foreach (($field['options'] ?? []) as $index => $option)
                                            <div class="checkbox-area mb-2">
                                                <label
                                                    for="bf_{{ \Illuminate\Support\Str::slug($field['name']) }}_{{ $index }}"
                                                    class="inline-flex items-center cursor-pointer">
                                                    <input class="hidden" type="checkbox"
                                                           name="branch_form[{{ $field['name'] }}][]"
                                                           value="{{ $option }}"
                                                           id="bf_{{ \Illuminate\Support\Str::slug($field['name']) }}_{{ $index }}"
                                                           @if (($field['validation'] ?? '') === 'required' && $index === 0) required @endif />
                                                    <span
                                                        class="h-4 w-4 border flex-none border-slate-100 dark:border-slate-800 rounded inline-flex ltr:mr-3 rtl:ml-3 relative transition-all duration-150 bg-slate-100 dark:bg-slate-900">
                                                        <img src="{{ asset('frontend/images/icon/ck-white.svg') }}"
                                                             alt=""
                                                             class="h-[10px] w-[10px] block m-auto opacity-0">
                                                    </span>
                                                    <span class="text-slate-500 dark:text-slate-400 text-sm leading-6">
                                                        {{ $option }}
                                                    </span>
                                                </label>
                                            </div>
                                        @endforeach
                                    </div>
                                @elseif(($field['type'] ?? '') === 'radio')
                                    <div class="col-span-12">
                                        @foreach(($field['options'] ?? []) as $opt)
                                            <div class="basicRadio mb-2">
                                                <label class="flex items-center cursor-pointer">
                                                    <input type="radio" class="hidden"
                                                           name="branch_form[{{ $field['name'] }}]"
                                                           value="{{ $opt }}"
                                                           @if(($field['validation'] ?? '')==='required') required @endif>
                                                    <span
                                                        class="flex-none bg-white dark:bg-slate-500 rounded-full border inline-flex ltr:mr-2 rtl:ml-2 relative transition-all duration-150 h-[16px] w-[16px] border-slate-400 dark:border-slate-600 dark:ring-slate-700"></span>
                                                    <span
                                                        class="text-slate-500 dark:text-slate-400 text-sm leading-6 capitalize">{{ $opt }}</span>
                                                </label>
                                            </div>
                                        @endforeach
                                    </div>
                                @elseif(($field['type'] ?? '') === 'dropdown')
                                    <div class="md:col-span-6 col-span-12 select2-lg">
                                        <select class="select2 form-control w-full mt-2 py-2" name="branch_form[{{ $field['name'] }}]" @if(($field['validation'] ?? '')==='required') required @endif>
                                            <option value="">{{ __('Select an option') }}</option>
                                            @foreach(($field['options'] ?? []) as $opt)
                                                <option value="{{ $opt }}" class="inline-block font-Inter font-normal text-sm text-slate-600">{{ $opt }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                @elseif(($field['type'] ?? '') === 'file')
                                    <div class="fileUpload">
                                        <input type="file" class="form-control" name="branch_form[{{ $field['name'] }}]" accept="image/*,.pdf,.doc,.docx,.txt" @if(($field['validation'] ?? '')==='required') required @endif>
                                        <div class="text-xs text-slate-500 dark:text-slate-400 mt-1">{{ __('Allowed file types: images, PDF, DOC, DOCX, TXT. Maximum size: 5MB') }}</div>
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                    <div class="text-center mt-6">
                        <button type="submit" class="btn btn-dark inline-flex items-center justify-center">
                            <iconify-icon class="text-xl ltr:mr-2 rtl:ml-2" icon="lucide:send"></iconify-icon>
                            {{ __('Submit') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <style>
        /* Prevent closing without submit */
        #branchFormModal .btn-close, .modal-header .close { display: none; }
        /* Ensure select2 dropdown overlays the modal correctly */
        .select2-container { z-index: 100000 !important; }
        .select2-container .select2-dropdown { z-index: 100001 !important; }
    </style>
</div>


