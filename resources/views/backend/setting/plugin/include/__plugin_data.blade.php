<form action="{{ route('admin.settings.plugin.update',$plugin->id) }}" method="post" class="space-y-5">
    @csrf
    <div class="flex items-center justify-between !mt-0">
        <h3 class="text-xl font-medium dark:text-white capitalize">{{ __('Update').' '. $plugin->name }}</h3>
        <button type="button" class="text-slate-400 bg-transparent hover:text-slate-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center
                    dark:hover:bg-slate-600 dark:hover:text-white" data-bs-dismiss="modal">
            <svg aria-hidden="true" class="w-5 h-5 dark:fill-white" fill="#000000" viewbox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10
                        11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
            </svg>
            <span class="sr-only">Close modal</span>
        </button>
    </div>
    @foreach(json_decode($plugin->data) as $key => $value)
        <div class="input-area">
            <label for="" class="form-label">{{ ucwords(str_replace('_',' ',$key)) }}</label>
            <input type="text" name="data[{{ $key }}]" class="form-control mb-0" value="{{ $value }}" required=""/>
        </div>
    @endforeach

    <div class="input-area max-w-xs">
        <div class="input-area relative flex items-center space-x-7">
            <label class="form-label !w-auto" for="">{{ __('Status:') }}</label>
            <div class="form-switch ps-0">
                <input type="hidden" value="0" name="status">
                <label class="relative inline-flex h-6 w-[46px] items-center rounded-full transition-all duration-150 cursor-pointer">
                    <input type="checkbox" name="status" value="1" class="sr-only peer" @if($plugin->status) checked @endif>
                    <span class="w-11 h-6 bg-gray-200 peer-focus:outline-none ring-0 rounded-full peer dark:bg-gray-900 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-black-500"></span>
                </label>
            </div>
        </div>
    </div>

    <div class="action-btns text-right">
        <button type="submit" class="btn btn-dark inline-flex items-center justify-center mr-2">
            <iconify-icon class="text-xl ltr:mr-2 rtl:ml-2" icon="lucide:check"></iconify-icon>
            {{ __(' Save Changes') }}
        </button>
        <a
            href="#"
            class="btn btn-danger inline-flex items-center justify-center"
            data-bs-dismiss="modal"
            aria-label="Close"
        >
            <iconify-icon class="text-xl ltr:mr-2 rtl:ml-2" icon="lucide:x"></iconify-icon>
            {{ __('Close') }}
        </a>
    </div>
</form>
