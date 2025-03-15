<div class="modal fade fixed top-0 left-0 hidden w-full h-full outline-none overflow-x-hidden overflow-y-auto" id="newTicketModal" tabindex="-1" aria-labelledby="newTicketModal" aria-hidden="true">
    <div class="modal-dialog top-1/2 !-translate-y-1/2 relative w-auto pointer-events-none">
        <div class="modal-content border-none shadow-lg relative flex flex-col w-full pointer-events-auto bg-white dark:bg-dark bg-clip-padding rounded-md outline-none text-current">
            <div class="modal-body popup-body">
                <div class="flex items-center justify-between p-5">
                    <h3 class="text-xl font-medium dark:text-white capitalize">
                        {{ __('Create New Ticket') }}
                    </h3>
                    <button type="button" class="text-slate-400 bg-transparent hover:text-slate-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center
                                dark:hover:bg-slate-600 dark:hover:text-white" data-bs-dismiss="modal">
                        <svg aria-hidden="true" class="w-5 h-5 dark:fill-white" fill="#000000" viewbox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                        </svg>
                        <span class="sr-only">Close modal</span>
                    </button>
                </div>
                <div class="p-6 pt-0">
                    <form action="{{ route('user.ticket.new-store') }}" method="post" enctype="multipart/form-data">
                        @csrf
                        <div class="space-y-4">
                            <!-- Ticket Title -->
                            <div class="input-area relative">
                                <label for="title" class="form-label">
                                    {{ __('Ticket Title') }}
                                </label>
                                <input
                                    type="text"
                                    class="form-control"
                                    name="title"
                                    id="title"
                                    placeholder="Ticket Title"
                                    required
                                >
                            </div>

                            <!-- Ticket Type -->
                            <div class="input-area">
                                <label for="" class="form-label">
                                    {{ __('Ticket Type') }}
                                </label>
                                <select name="label" class="form-control" required>
                                    <option value="">{{ __('Select Type') }}</option>
                                    @foreach($labels as $id => $name)
                                        <option value="{{ $id }}">{{ $name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Ticket Priority -->
                            <div class="input-area">
                                <label for="" class="form-label">
                                    {{ __('Ticket Priority') }}
                                </label>
                                <select name="priority" class="form-control" required>
                                    <option value="">{{ __('Select Priority') }}</option>
                                    @foreach(\Coderflex\LaravelTicket\Enums\Priority::cases() as $priority)
                                        <option value="{{ $priority->value }}">
                                            {{ $priority->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="input-area">
                                <label for="" class="form-label">
                                    {{ __('Attach Image') }}
                                </label>
                                <div class="filegroup">
                                    <label>
                                        <input type="file" id="attach-input" class="w-full hidden" name="attach" accept=".gif, .jpg, .png">
                                        <span class="w-full h-[40px] file-control flex items-center custom-class">
                                            <span class="flex-1 overflow-hidden text-ellipsis whitespace-nowrap">
                                                <span id="fileTile" class="text-slate-400">Choose a file or drop it here...</span>
                                            </span>
                                            <span class="file-name flex-none cursor-pointer border-l px-4 border-slate-200 dark:border-slate-700 h-full inline-flex items-center bg-slate-100 dark:bg-slate-900 text-slate-600 dark:text-slate-400 text-sm rounded-tr rounded-br font-normal">Browse</span>
                                        </span>
                                    </label>
                                </div>
                            </div>

                            <!-- Ticket Descriptions -->
                            <div class="input-area relative">
                                <label for="message" class="form-label">
                                    {{ __('Ticket Descriptions') }}
                                </label>
                                <textarea
                                    class="form-control textarea"
                                    rows="5"
                                    name="message"
                                    id="message"
                                    required
                                ></textarea>
                            </div>
                        </div>

                        <div class="action-btns text-right mt-10">
                            <button type="submit" class="btn btn-dark inline-flex items-center justify-center mr-2">
                                <iconify-icon class="text-xl ltr:mr-2 rtl:ml-2" icon="lucide:check"></iconify-icon>
                                {{ __('Create Ticket') }}
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
