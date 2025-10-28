  @php
        $accountComments = \App\Models\Comment::where('type', 'accounts')->where('status', true)->orderBy('title')->get(['id','title','description']);
    @endphp
    <div class="modal fade fixed top-0 left-0 hidden w-full h-full outline-none overflow-x-hidden overflow-y-auto" id="accountActionModal" tabindex="-1" aria-labelledby="accountActionModal" aria-hidden="true">
        <div class="modal-dialog top-1/2 !-translate-y-1/2 relative max-w-2xl w-full pointer-events-none">
            <div class="modal-content border-none shadow-lg relative flex flex-col w-full pointer-events-auto bg-white dark:bg-dark bg-clip-padding rounded-md outline-none text-current">
                <div class="relative bg-white rounded-lg shadow dark:bg-dark">
                    <div class="flex items-center justify-between p-5 border-b rounded-t dark:border-slate-600">
                        <h3 class="text-xl font-medium dark:text-white capitalize">{{ __('Account Action') }}</h3>
                        <button type="button" class="text-slate-400 bg-transparent hover:text-slate-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-slate-600 dark:hover:text-white" data-bs-dismiss="modal">
                            <svg aria-hidden="true" class="w-5 h-5 fill-black dark:fill-white" viewbox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
                            <span class="sr-only">{{ __('Close modal') }}</span>
                        </button>
                    </div>
                    <div class="p-6">
                        <form method="post" onsubmit="return false;">
                            @csrf
                            <input type="hidden" id="account-action-login-id" value="">
                            <input type="hidden" id="account-action-id" value="">
                            <div class="space-y-5">
                                <ul class="account-details-list h-full">
                                    <li class="flex items-baseline relative overflow-hidden py-3">
                                        <span class="font-medium dark:text-white">{{ __('User') }}</span>
                                        <span class="flex-1 h-full border-b border-dashed mx-1"></span>
                                        <span class="ml-auto dark:text-white" id="account-action-username">-</span>
                                    </li>
                                    <li class="flex items-baseline relative overflow-hidden py-3">
                                        <span class="font-medium dark:text-white">{{ __('Account Type') }}</span>
                                        <span class="flex-1 h-full border-b border-dashed mx-1"></span>
                                        <span class="ml-auto dark:text-white" id="account-action-type">-</span>
                                    </li>
                                    <li class="flex items-baseline relative overflow-hidden py-3">
                                        <span class="font-medium dark:text-white">{{ __('Group') }}</span>
                                        <span class="flex-1 h-full border-b border-dashed mx-1"></span>
                                        <span class="ml-auto dark:text-white" id="account-action-group">-</span>
                                    </li>
                                </ul>
                                
                                <div class="input-area">
                                    <label class="form-label" for="account-comment-select">{{ __('Comments') }}</label>
                                    <select id="account-comment-select" class="form-control select2 h-[42px]">
                                        <option value="">{{ __('Select a comment') }}</option>
                                        @forelse($accountComments as $comment)
                                            <option value="{{ $comment->id }}" data-description='@json($comment->description)'>{{ $comment->title }}</option>
                                        @empty
                                            <option value="" disabled>{{ __('No active account comments') }}</option>
                                        @endforelse
                                    </select>
                                    <p class="text-xs text-slate-400 mt-1">{{ __('Selecting a title will prefill the description. You can edit it further.') }}</p>
                                </div>
                                <div class="input-area">
                                    <label for="" class="form-label">{{ __('Detail Message') }}</label>
                                    <textarea id="account-comment-message" class="form-control basicTinymce mb-0" rows="6" placeholder="{{  __('Enter Message') }}"></textarea>
                                </div>
                            </div>
                            <div class="action-btns text-right mt-10">
                                <button type="button" class="btn btn-dark inline-flex items-center justify-center mr-2 approve-account-modal">{{ __('Approve') }}</button>
                                <button type="button" class="btn btn-danger inline-flex items-center justify-center reject-account-modal">{{ __('Reject') }}</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>