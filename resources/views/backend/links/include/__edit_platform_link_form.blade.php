<form action="{{ route('admin.links.platform.update') }}" method="post">
    @csrf
    @method('PUT')
    <input type="hidden" name="id" value="{{ $platformLink->id }}">
    <div class="space-y-5">
        <div class="input-area">
            <label for="" class="form-label">
                <span class="shift-Away inline-flex items-center gap-1" data-tippy-content="Enter the platform title">
                    {{ __('Title') }}
                    <iconify-icon icon="mdi:information-slab-circle-outline" class="text-[16px]"></iconify-icon>
                </span>
            </label>
            <input type="text" name="title" class="form-control" value="{{ $platformLink->title }}">
        </div>
        <div class="input-area">
            <label for="" class="form-label">
                <span class="shift-Away inline-flex items-center gap-1" data-tippy-content="Enter the download or access URL">
                    {{ __('Link') }}
                    <iconify-icon icon="mdi:information-slab-circle-outline" class="text-[16px]"></iconify-icon>
                </span>
            </label>
            <input type="url" name="link" class="form-control" value="{{ $platformLink->link }}">
        </div>
        <div class="input-area relative">
            <label for="" class="form-label">
                <span class="shift-Away inline-flex items-center gap-1" data-tippy-content="Select the platform type (e.g., MT4/MT5)">
                    {{ __('Platform') }}
                    <iconify-icon icon="mdi:information-slab-circle-outline" class="text-[16px]"></iconify-icon>
                </span>
            </label>
            @php
                $traderTypes = [
                    \App\Enums\TraderType::MT5,
                    \App\Enums\TraderType::X9,
                    \App\Enums\TraderType::CTRADER,
                    \App\Enums\TraderType::All,
                ];
            @endphp
            <select name="platform" class="form-control">
                @foreach ($traderTypes as $type)
                    <option value="{{ $type }}" @selected($type == $platformLink->platform)>{{ ucfirst($type) }}</option>
                @endforeach
            </select>
        </div>
        <div class="input-area relative">
            <label for="" class="form-label">
                <span class="shift-Away inline-flex items-center gap-1" data-tippy-content="Choose the compatible OS">
                    {{ __('Operating System') }}
                    <iconify-icon icon="mdi:information-slab-circle-outline" class="text-[16px]"></iconify-icon>
                </span>
            </label>
            <select name="os" class="form-control">
                <option value="window" @selected($platformLink->os == 'window')>{{ __('Window') }}</option>
                <option value="mac" @selected($platformLink->os == 'mac')>{{ __('MAC') }}</option>
                <option value="android" @selected($platformLink->os == 'android')>{{ __('Android') }}</option>
                <option value="ios" @selected($platformLink->os == 'ios')>{{ __('iOS') }}</option>
                <option value="android_apk" @selected($platformLink->os == 'android_apk')>{{ __('Android APK') }}</option>
                <option value="web" @selected($platformLink->os == 'web')>{{ __('Web') }}</option>
            </select>
        </div>
        <div class="input-area">
            <div class="flex items-center space-x-7 flex-wrap">
                <label class="form-label !w-auto pt-0">
                    <span class="shift-Away inline-flex items-center gap-1" data-tippy-content="Enable or disable this link">
                        {{ __('Status') }}
                        <iconify-icon icon="mdi:information-slab-circle-outline" class="text-[16px]"></iconify-icon>
                    </span>
                </label>
                <div class="form-switch ps-0">
                    <input class="form-check-input" type="hidden" value="0" name="status">
                    <label class="relative inline-flex h-6 w-[46px] items-center rounded-full transition-all duration-150 cursor-pointer">
                        <input type="checkbox" name="status" value="1" class="sr-only peer" @checked($platformLink->status)>
                        <span class="w-11 h-6 bg-gray-200 peer-focus:outline-none ring-0 rounded-full peer dark:bg-gray-900 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-black-500"></span>
                    </label>
                </div>
            </div>
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
