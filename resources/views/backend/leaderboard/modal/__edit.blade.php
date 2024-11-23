<div class="modal fade fixed top-0 left-0 hidden w-full h-full outline-none overflow-x-hidden overflow-y-auto" id="editLeaderBoardModal" tabindex="-1" aria-labelledby="editLeaderBoardModal" aria-hidden="true">
    <div class="modal-dialog top-1/2 !-translate-y-1/2 relative w-auto pointer-events-none">
        <div class="modal-content border-none shadow-lg relative flex flex-col w-full pointer-events-auto bg-white bg-clip-padding rounded-md outline-none text-current">
            <div class="modal-body popup-body">
                <div class="flex items-start justify-between gap-3 p-5">
                    <div>
                        <h3 class="text-xl font-medium dark:text-white capitalize mb-1">
                            {{ __('Edit Leaderboard Ranking') }}
                            <span id="modalTitle"></span>
                        </h3>
                    </div>
                    <button type="button" class="text-slate-400 bg-transparent hover:text-slate-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-slate-600 dark:hover:text-white" data-bs-dismiss="modal">
                        <svg aria-hidden="true" class="w-5 h-5" fill="#000000" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                        </svg>
                        <span class="sr-only">Close modal</span>
                    </button>
                </div>
                <div class="p-6 pt-3">
                    <form action="{{ route('admin.leaderboard-rankings.store') }}" method="post">
                        @csrf
                        <div class="space-y-5">
                            <div class="input-area">
                                <label for="" class="form-label">{{ __('Ranking') }}</label>
                                <input type="text" name="ranking" id="ranking-input" class="form-control">
                            </div>
                            <div class="input-area">
                                <label for="" class="form-label">{{ __('Name') }}</label>
                                <input type="text" name="user_name" id="user_name-input" class="form-control">
                            </div>
                            
                            <div class="input-area">
                                <label for="" class="form-label">{{ __('Profit') }}</label>
                                <input type="text" name="profit" id="profit-input" class="form-control">
                            </div>
                            <div class="input-area">
                                <label for="" class="form-label">{{ __('Equity') }}</label>
                                <input type="text" name="equity" id="equity-input" class="form-control">
                            </div>
                            <div class="input-area">
                                <label for="" class="form-label">{{ __('Account Size') }}</label>
                                <input type="text" name="account_size" id="account_size-input" class="form-control">
                            </div>
                            <div class="input-area">
                                <label for="" class="form-label">{{ __('Gain') }}</label>
                                <input type="text" name="gain" id="gain-input" class="form-control">
                            </div>

                            <div class="input-area">
                                <label for="" class="form-label">{{ __('Category') }}</label>
                                <select name="leaderboard_rankings_category_id" class="select2  form-control" id="category-input" data-placeholder="Select Category">
                                    @foreach ($rankings_categories as $category)
                                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            
                            <input type="hidden" value="" name="id" id="ranking_id-input">
      


                            
                        </div>
                        <div class="action-btns text-right mt-10">
                            <button type="submit" class="btn btn-dark inline-flex items-center justify-center mr-2">
                                <iconify-icon class="text-xl ltr:mr-2 rtl:ml-2" icon="lucide:check"></iconify-icon>
                                {{ __('Save Changes') }}
                            </button>
                            <a href="#"
                               class="btn btn-danger inline-flex items-center justify-center"
                               data-bs-dismiss="modal"
                               aria-label="Close">
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


