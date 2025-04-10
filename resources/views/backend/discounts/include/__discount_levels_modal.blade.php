<div class="modal fade fixed top-0 left-0 hidden w-full h-full outline-none overflow-x-hidden overflow-y-auto" id="discountLevels{{ $discount_code->id }}" tabindex="-1" aria-labelledby="discountLevels{{ $discount_code->id }}ModalLabel" aria-modal="true"
    role="dialog">
    <div class="modal-dialog modal-lg top-1/2 !-translate-y-1/2 relative w-auto pointer-events-none">
        <div class="modal-content border-none shadow-lg relative flex flex-col w-full pointer-events-auto bg-white bg-clip-padding rounded-md outline-none text-current">
            <div class="flex items-center justify-between p-5">
                <h3 class="text-xl font-medium dark:text-white capitalize" id="discountLevels{{ $discount_code->id }}Label">
                    Manage Discount Levels ({{ $discount_code->code_name }})
                </h3>
                <button type="button" class="text-slate-400 bg-transparent hover:text-slate-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-slate-600 dark:hover:text-white" data-bs-dismiss="modal">
                    <svg aria-hidden="true" class="w-5 h-5" fill="#000000" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                    </svg>
                    <span class="sr-only">Close modal</span>
                </button>
            </div>
            <div class="modal-body p-6 pt-0">
                <button type="button" class="btn btn-outline-dark btn-sm generateCreate" style="min-width: 0px; float: right; position: relative; top: -13px">Add Level</button>
                <form action="{{ route('admin.discount.levels.update') }}" method="post" class="discount-form">
                    @csrf

                    <input type="hidden" name="discount_id" value="{{ $discount_code->id }}">

                    <div class="grid grid-cols-12 mb-2" style="clear: both">
                      <div class="xl:col-span-3 col-span-12">
                        <center><small><b>Amount From</b></small></center>
                      </div>
                      <div class="xl:col-span-3 col-span-12">
                          <center><small><b>Amount To</b></small></center>
                      </div>
                      <div class="xl:col-span-3 col-span-12">
                        <center><small><b>Amount </b></small></center>
                      </div>
                      <div class="xl:col-span-2 col-span-12">
                        <center><small><b>Type</b></small></center>
                      </div>
                      <div class="col-span-1">

                      </div>
                    </div>

                    <div class="discount-levels">
                        @if ($discount_code->discount_levels != null)
                            @foreach ($discount_code->discount_levels as $level)
                                <div class="option-remove-row grid grid-cols-12 gap-5 discount-level">
                                    <div class="xl:col-span-3 col-span-12">
                                    <div class="input-area">
                                        <input name="data[{{ $loop->index }}][amount_from]" min="0" value="{{ $level['amount_from'] }}" class="form-control" required type="number" placeholder="0">
                                    </div>
                                    </div>
                                    <div class="xl:col-span-3 col-span-12">
                                    <div class="input-area">
                                        <input name="data[{{ $loop->index }}][amount_to]" min="0" value="{{ $level['amount_to'] }}" class="form-control" required type="number" placeholder="100">
                                    </div>
                                    </div>
                                    <div class="xl:col-span-3 col-span-12">
                                    <div class="input-area">
                                        <input name="data[{{ $loop->index }}][amount]" min="0" value="{{ $level['amount'] }}" class="form-control" required type="number" placeholder="10">
                                    </div>
                                    </div>
                                    <div class="xl:col-span-2 col-span-12">
                                    <div class="input-area">
                                        <select name="data[{{ $loop->index }}][type]" class="form-control w-full mb-3">
                                        <option value="fixed" selected="">$</option>
                                        <option value="percentage">%</option>
                                        </select>
                                    </div>
                                    </div>
                                    <div class="col-span-1">
                                    <button class="btn-dark h-[32px] w-[32px] flex items-center justify-center rounded-full text-xl delete-option-row delete_desc" type="button">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24">
                                        <path fill="currentColor" d="M19 6.41L17.59 5L12 10.59L6.41 5L5 6.41L10.59 12L5 17.59L6.41 19L12 13.41L17.59 19L19 17.59L13.41 12z"></path>
                                        </svg>
                                    </button>
                                    </div>
                                </div>
                            @endforeach
                        @endif
                    </div>
                    
                    <div class="text-right mt-3">
                        <button type="submit" class="btn btn-dark inline-flex items-center justify-center">
                            <iconify-icon class="text-xl ltr:mr-2 rtl:ml-2" icon="lucide:check"></iconify-icon>
                            Save Changes
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

