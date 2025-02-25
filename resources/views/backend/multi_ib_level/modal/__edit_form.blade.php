<form action="{{ route('admin.multi-ib-level.update', $level->id) }}" method="post" class="space-y-4">
    @csrf
    @method('PUT')

    <div class="input-area">
        <label class="form-label">{{ __('Title:') }}</label>
        <input type="text" name="title" value="{{ old('title', $level->title) }}" class="form-control" placeholder="Level Title" required/>
    </div>
    
    <div class="input-area">
        <label class="form-label">{{ __('Level Order:') }}</label>
        <input type="number" name="level_order" value="{{ old('level_order', $level->level_order) }}" class="form-control" placeholder="Level Order" required/>
    </div>

    <div class="input-area">
        <div class="flex items-center space-x-7 flex-wrap">
            <label class="form-label !w-auto pt-0 !mb-0">{{ __('Status:') }}</label>
            <div class="form-switch ps-0" style="line-height: 0;">
                <input class="form-check-input" type="hidden" value="0" name="status">
                <label class="deposit-status relative inline-flex h-6 w-[46px] items-center rounded-full transition-all duration-150 cursor-pointer toggle-switch">
                    <input type="checkbox" name="status" value="1" class="sr-only peer" @if($level->status) checked @endif>
                    <span class="w-11 h-6 bg-gray-200 peer-focus:outline-none ring-0 rounded-full peer dark:bg-gray-900 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-black-500"></span>
                </label>
            </div>
        </div>
    </div>

    <div class="input-area text-right">
        <button type="submit" class="btn btn-dark inline-flex items-center justify-center">
            {{ __('Save Changes') }}
        </button>
    </div>
</form>
