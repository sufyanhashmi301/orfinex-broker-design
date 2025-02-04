<div class="modal fade fixed top-0 left-0 hidden w-full h-full outline-none overflow-x-hidden overflow-y-auto"
    id="editManualMethod" tabindex="-1" aria-labelledby="editManualMethod" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable modal-lg top-1/2 !-translate-y-1/2 relative w-auto pointer-events-none" style="max-height: 75vh">
        <div
            class="modal-content border-none shadow-lg relative flex flex-col w-full pointer-events-auto bg-white dark:bg-dark bg-clip-padding rounded-md outline-none text-current">
            <div class="modal-body popup-body">
                <div class="flex items-start justify-between gap-3 p-5">
                    <h5 class="modal-title" id="editManualMethodLabel">{{ __('Edit Options for Manual KYC') }}</h5>
                    <button type="button"
                        class="text-slate-400 bg-transparent hover:text-slate-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-slate-600 dark:hover:text-white"
                        data-bs-dismiss="modal">
                        <svg aria-hidden="true" class="w-5 h-5 dark:fill-white" fill="#000000" viewbox="0 0 20 20"
                            xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd"
                                d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                                clip-rule="evenodd"></path>
                        </svg>
                        <span class="sr-only">Close modal</span>
                    </button>
                </div>
                <div class="p-6 pt-0 edit-plugin-section">
                    <form action="{{ route('admin.kyc_method.update', ['id' => $manual_method->id]) }}" method="post" class="space-y-4">
                        @csrf

                        <input type="hidden" name="type" value="manual">

                        <h6>Options</h6>

                        @foreach ($manual_method->data as $option)
                          {{-- @dd($option['status']) --}}
                          <div class="single-gateway flex items-center justify-between border rounded dark:border-slate-700 py-3 px-4">
                            <div class="gateway-name flex items-center gap-2">
                                <div class="gateway-icon mr-1">
                                    <iconify-icon class="text-3xl dark:text-slate-300" icon="mdi:id-card-outline"></iconify-icon>
                                </div>
                                <div class="gateway-title">
                                    <h4 class="text-base" style="display: inline-block;">{{ $option['name'] }}</h4>
                                    @if ($option['status'])
                                      <div class="gateway-status" style="display: inline-block; margin-left: 10px">
                                        <div class="badge badge-{{ $option['status'] ? 'success' : 'danger' }} capitalize">{{ $option['status'] ? 'Active' : 'Inactive' }}</div>
                                      </div>
                                    @endif
                                </div>
                            </div>
                            <div class="gateway-right flex items-center gap-2">
                                
                                <div class="gateway-edit">
                                    <button type="button" style="display: inline-block" class="action-btn show-fields cursor-pointer editLevel2 dark:text-slate-300" data-id="{{ $loop->index }}" >
                                        <iconify-icon icon="lucide:chevron-down" style="position: relative; top: 2px"></iconify-icon>
                                    </button>
                                    @if (!$option['status'])
                                      <a href="{{ route('admin.kyc_method.option_toggle', ['action' => 'active', 'field_name' => $option['name']]) }}" style="display: inline-block" class="action-btn cursor-pointer dark:text-slate-300" data-id="{{ $loop->index }}" >
                                        <iconify-icon style="font-size: 13px; left: 50%; position: relative; transform:translateX(-50%)" icon="lucide:check" style="font-size: 13px"></iconify-icon>
                                      </a>
                                    @else
                                      <a href="{{ route('admin.kyc_method.option_toggle', ['action' => 'disabled', 'field_name' => $option['name']]) }}" style="display: inline-block" class="action-btn cursor-pointer dark:text-slate-300" data-id="{{ $loop->index }}" >
                                        <iconify-icon style="font-size: 13px; left: 50%; position: relative; transform:translateX(-50%)" icon="lucide:ban" style="font-size: 13px"></iconify-icon>
                                      </a>
                                    @endif
                                    <a href="{{ route('admin.kyc_method.delete_manual_method_option', ['option_name' => $option['name']]) }}" style="display: inline-block" class="action-btn cursor-pointer dark:text-slate-300" data-id="{{ $loop->index }}" >
                                      <iconify-icon icon="lucide:trash-2" style="font-size: 13px; left: 50%; position: relative; transform:translateX(-50%)"></iconify-icon>
                                    </a>
                                </div>
                            </div>
                          </div>

                          <div class="options-container" data-id="{{ $loop->index }}" style="display: none">
                            <div class="input-area mb-3">
                              <label class="form-label" for="">{{ __('Name') }}</label>
                              <input type="text" name="data[{{ $loop->index }}][name]" value="{{ $option['name'] }}" class="form-control" placeholder="KYC Type Name" required />
                            </div>
                          
                            <input type="hidden" name="data[{{ $loop->index }}][status]" value="{{ $option['status'] }}" class="form-control" placeholder="KYC Type Name" required />

                            <label style="display: inline-block" class="form-label" for="">{{ __('Fields') }}</label>
                            <div>
                              <a href="javascript:void(0)" data-index={{ $loop->index }} class="edit-modal-add-field btn btn-outline-dark btn-sm inline-flex items-center justify-center mb-3">
                                {{ __('Add Field') }}
                              </a>
                            </div>
                            @php
                              $field_index = 1; // Start indexing from 1 to match your desired structure
                            @endphp
                            @foreach ($option['fields'] as $field)
                              <div class="option-remove-row grid grid-cols-12 gap-5"  >
                                <div class="xl:col-span-4 col-span-12">
                                  <div class="input-area">
                                    <input name="data[{{ $loop->parent->index }}][fields][{{ $field_index }}][name]" class="form-control" type="text" value="{{ $field['name'] }}" required="" placeholder="Field Name">
                                  </div>
                                </div>
                                <div class="xl:col-span-4 col-span-12">
                                  <div class="input-area">
                                    <select name="data[{{ $loop->parent->index }}][fields][{{ $field_index }}][type]" class="form-control w-full mb-3">
                                      <option value="text" {{ $field['type'] == 'text' ? 'selected' : '' }}>Input Text</option>
                                      <option value="textarea" {{ $field['type'] == 'textarea' ? 'selected' : '' }}>Textarea</option>
                                      <option value="file" {{ $field['type'] == 'file' ? 'selected' : '' }}>File upload</option>
                                    </select>
                                  </div>
                                </div>
                                <div class="xl:col-span-3 col-span-12">
                                  <div class="input-area">
                                    <select name="data[{{ $loop->parent->index }}][fields][{{ $field_index }}][validation]" class="form-control w-full mb-3">
                                      <option value="required" {{ $field['validation'] == 'required' ? 'selected' : '' }}>Required</option>
                                      <option value="nullable" {{ $field['validation'] == 'nullable' ? 'selected' : '' }}>Optional</option>
                                    </select>
                                  </div>
                                </div>
                                <div class="col-span-1">
                                  <button class="btn-dark h-[32px] w-[32px] flex items-center justify-center rounded-full text-xl edit-modal-delete-field delete_desc" type="button">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24">
                                      <path fill="currentColor" d="M19 6.41L17.59 5L12 10.59L6.41 5L5 6.41L10.59 12L5 17.59L6.41 19L12 13.41L17.59 19L19 17.59L13.41 12z"></path>
                                    </svg>
                                  </button>
                                </div>
                              </div>
                              @php
                                $field_index++; // Increment the index for the next field
                              @endphp
                            @endforeach
                          </div>
                        @endforeach
                        
                        
                    
                        <div class="input-area text-right">
                            <button type="submit" class="btn btn-dark inline-flex items-center justify-center">
                                {{ __('Save Changes') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
