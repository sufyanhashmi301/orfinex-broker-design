<form action="{{ route('admin.ticket.store') }}" method="post" enctype="multipart/form-data">
    @csrf
    <div class="space-y-4">
        <div class="input-area relative">
            <label for="title" class="form-label">
                <span class="shift-Away inline-flex items-center gap-1" data-tippy-content="Select the client this ticket is for">
                    {{ __('Ticket Client') }}
                    <iconify-icon icon="mdi:information-slab-circle-outline" class="text-[16px]"></iconify-icon>
                </span>
            </label>
            <select name="user_id" id="client_input" class="form-control">
                <option value="">{{ __('Select Client') }}</option>
            </select>
            @error('user_id')
                <span class="error">{{ $message }}</span>
            @enderror
        </div>

        <!-- Ticket Title -->
        <div class="input-area relative">
            <label for="title" class="form-label">
                <span class="shift-Away inline-flex items-center gap-1" data-tippy-content="Enter a brief subject for the ticket">
                    {{ __('Ticket Title') }}
                    <iconify-icon icon="mdi:information-slab-circle-outline" class="text-[16px]"></iconify-icon>
                </span>
            </label>
            <input
                type="text"
                class="form-control"
                name="title"
                id="title"
                placeholder="Ticket Title"
            >
            @error('title')
                <span class="error">{{ $message }}</span>
            @enderror
        </div>

        <!-- Ticket Type -->
        <div class="input-area">
            <label for="" class="form-label">
                <span class="shift-Away inline-flex items-center gap-1" data-tippy-content="Choose the type of issue or request">
                    {{ __('Ticket Type') }}
                    <iconify-icon icon="mdi:information-slab-circle-outline" class="text-[16px]"></iconify-icon>
                </span>
            </label>
            <select name="label" class="select2 form-control">
                <option value="">{{ __('Select Type') }}</option>
                @foreach($labels as $id => $name)
                    <option value="{{ $id }}">{{ $name }}</option>
                @endforeach
            </select>
            @error('label')
                <span class="error">{{ $message }}</span>
            @enderror
        </div>

        <!-- Ticket Priority -->
        <div class="input-area">
            <label for="" class="form-label">
                <span class="shift-Away inline-flex items-center gap-1" data-tippy-content="Set the urgency level of the ticket">
                    {{ __('Ticket Priority') }}
                    <iconify-icon icon="mdi:information-slab-circle-outline" class="text-[16px]"></iconify-icon>
                </span>
            </label>
            <select name="priority" class="select2 form-control">
                <option value="">{{ __('Select Priority') }}</option>
                @foreach(\Coderflex\LaravelTicket\Enums\Priority::cases() as $priority)
                    <option value="{{ $priority->value }}">
                        {{ $priority->name }}
                    </option>
                @endforeach
            </select>
            @error('priority')
                <span class="error">{{ $message }}</span>
            @enderror
        </div>

        <div class="input-area">
            <label for="" class="form-label">
                <span class="shift-Away inline-flex items-center gap-1" data-tippy-content="Assign the ticket to a support agent">
                    {{ __('Agent') }}
                    <iconify-icon icon="mdi:information-slab-circle-outline" class="text-[16px]"></iconify-icon>
                </span>
            </label>
            <select name="assigned_to" id="assigned_to" class="form-control">
                <option value="">{{ __('Select Agent') }}</option>
                @foreach($staff as $staff)
                    <option
                        data-avatar="{{ getFilteredPath($staff->avatar, 'fallback/staff.png') }}"
                        data-role="{{ $staff->getRoleNames()->first() }}"
                        value="{{ $staff->id }}">
                        {{ $staff->first_name.' '.$staff->last_name }}
                    </option>
                @endforeach
            </select>
            @error('assigned_to')
                <span class="error">{{ $message }}</span>
            @enderror
        </div>

        <div class="input-area">
            <label for="" class="form-label">
                <span class="shift-Away inline-flex items-center gap-1" data-tippy-content="Upload a file or screenshot if needed">
                    {{ __('Attach Image') }}
                    <iconify-icon icon="mdi:information-slab-circle-outline" class="text-[16px]"></iconify-icon>
                </span>
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
                <span class="shift-Away inline-flex items-center gap-1" data-tippy-content="Add details about the issue or request">
                    {{ __('Ticket Descriptions') }}
                    <iconify-icon icon="mdi:information-slab-circle-outline" class="text-[16px]"></iconify-icon>
                </span>
            </label>
            <textarea
                class="form-control textarea"
                rows="5"
                name="message"
                id="message"
            ></textarea>
            @error('message')
                <span class="error">{{ $message }}</span>
            @enderror
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
