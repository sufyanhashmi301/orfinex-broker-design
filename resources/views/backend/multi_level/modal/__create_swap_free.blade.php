<div class="modal fade fixed top-0 left-0 hidden w-full h-full outline-none overflow-x-hidden overflow-y-auto" id="addSwapFreeLevelModal" tabindex="-1" aria-labelledby="addLevelModal" aria-hidden="true">
    <div class="modal-dialog top-1/2 !-translate-y-1/2 relative w-auto pointer-events-none">
        <div class="modal-content border-none shadow-lg relative flex flex-col w-full pointer-events-auto bg-white dark:bg-dark bg-clip-padding rounded-md outline-none text-current">
            <div class="modal-body popup-body">
                <div class="flex items-start justify-between p-5">
                    <div>
                        <h3 class="text-xl font-medium dark:text-white capitalize mb-2">
                            {{ __('Multi-Level Partner Program Settings') }}
                        </h3>
                        <p class="text-slate-500 dark:text-slate-300">
                            {{ __('Change your Multi-Level Partner Program settings.') }}
                        </p>
                    </div>
                    <button type="button" class="text-slate-400 bg-transparent hover:text-slate-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center
                                dark:hover:bg-slate-600 dark:hover:text-white" data-bs-dismiss="modal">
                        <svg aria-hidden="true" class="w-5 h-5 dark:fill-white" fill="#000000" viewbox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10
                                    11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                        </svg>
                        <span class="sr-only">Close modal</span>
                    </button>
                </div>
                <div class="p-6">
                    <form action="{{route('admin.swap-multi-level.store')}}" method="post">
                        @csrf
                        <input type="hidden" name="type" value="{{the_hash(\App\Enums\MultiLevelType::SWAP_FREE)}}" >

                        <div class="grid lg:grid-cols-2 grid-cols-1 gap-5">
                            <div class="input-area">
                                <label for="" class="form-label">{{ __('Title') }}</label>
                                <input
                                    type="text"
                                    name="title"
                                    class="form-control mb-0"
                                    placeholder="Title"
                                    required
                                />
                            </div>
                            <input type="hidden" name="forex_scheme_id" value="{{$schema->id}}">
                            <div class="input-area">
                                <label for="" class="form-label">{{ __('Level Order') }}</label>
                                <input
                                    type="text"
                                    name="level_order"
                                    class="form-control mb-0"
                                    placeholder="2"
                                    required
                                />
                            </div>
                            <div class="lg:col-span-2 input-area">
                                <label for="" class="form-label">{{ __('Group Tag') }}</label>
                                <input
                                    type="text"
                                    name="group_tag"
                                    class="form-control mb-0"
                                    placeholder="real\Promo\nb50s"
                                    required
                                />
                            </div>
                            <div class="lg:col-span-2 input-area">
                                <label for="" class="form-label">
                                    {{ __('Select Rebate Rules') }}
                                </label>
                                <select name="rebate_rules[]" class="select2 form-control w-full" multiple="multiple">
                                    @foreach($rebateRules as $rebateRule)
                                        <option  value="{{ $rebateRule->id }}">
                                            {{ $rebateRule->title  }}
                                        </option>
                                    @endforeach

                                </select>
                                <div class="invalid-feedback" id="rebate-rules" style="display: none;"></div>
                            </div>
                            <div class="lg:col-span-2 input-area">
                                <label for="" class="form-label">{{ __('Short Description') }}</label>
                                <textarea
                                    name="description"
                                    class="form-control mb-0"
                                    placeholder="Short Description"
                                    required
                                ></textarea>
                            </div>
                            <div class="lg:col-span-2 input-area">
                                <label for="status" class="form-label">{{ __('Status') }}</label>
                                <select name="status" class="form-control w-full">
                                    <option value="1">{{ __('Enable') }}</option>
                                    <option value="0">{{ __('Disable') }}</option>
                                </select>
                            </div>
                        </div>
                        <div class="action-btns text-right mt-10">
                            <button type="submit" class="btn btn-dark inline-flex items-center justify-center mr-2">
                                <iconify-icon class="text-xl ltr:mr-2 rtl:ml-2" icon="lucide:check"></iconify-icon>
                                {{ __('Save') }}
                            </button>
                            <a href="#" class="btn btn-danger inline-flex items-center justify-center" data-bs-dismiss="modal" aria-label="Close">
                                <iconify-icon class="text-xl ltr:mr-2 rtl:ml-2" icon="lucide:x"></iconify-icon>
                                {{ __('Close') }}
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
