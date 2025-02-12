<form action="{{ route('admin.ticket.store') }}" method="post" enctype="multipart/form-data">
    @csrf
    <div class="space-y-4">
        <div class="input-area relative">
            <label for="title" class="form-label">
                {{ __('Ticket Client') }}
            </label>
            <select name="user_id" class="select2 form-control">
                <option value="">{{ __('Select Client') }}</option>
                @foreach($users as $user)
                    <option value="{{ $user->id }}">{{ $user->first_name.' '.$user->last_name }}</option>
                @endforeach
            </select>
        </div>

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
            <select name="label" class="select2 form-control" required>
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
            <select name="priority" class="select2 form-control" required>
                <option value="">{{ __('Select Priority') }}</option>
                @foreach(\Coderflex\LaravelTicket\Enums\Priority::cases() as $priority)
                    <option value="{{ $priority->value }}">
                        {{ $priority->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="input-area">
            <label for="" class="form-label">{{ __('Agent') }}</label>
            <select name="assigned_to" id="assigned_to" class="form-control">
                <option value="">{{ __('Select Agent') }}</option>
                @foreach($staff as $staff)
                    <option
                        data-avatar="{{ asset($staff->avatar ?? 'global/materials/user.png') }}"
                        data-role="{{ $staff->getRoleNames()->first() }}"
                        value="{{ $staff->id }}">
                        {{ $staff->first_name.' '.$staff->last_name }}
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
