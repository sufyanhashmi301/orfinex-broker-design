<form action="{{ route('admin.deal.note.update', $note->id) }}" method="post">
    @method('put')
    @csrf
    <div class="space-y-5">
        <div class="py-3 px-4 font-normal text-sm rounded-md bg-warning-500 bg-opacity-[14%]  text-white">
            <div class="flex items-center space-x-3 rtl:space-x-reverse">
                <iconify-icon class="text-lg flex-0 text-warning-500" icon="lucide:info"></iconify-icon>
                <p class="flex-1 text-warning-500 font-Inter">
                    {{ __('A New note will be create when stage changes to win or lost.') }}
                </p>
            </div>
        </div>
        <div class="input-area">
            <label for="" class="form-label">{{ __('Deal Stage') }}</label>
            <input type="text" name="title" class="form-control" value="{{ $note->title }}">
        </div>
        <div class="input-area">
            <label for="" class="form-label">{{ __('Remark') }}</label>
            <textarea name="details" rows="5" class="form-control block w-full bg-transparent dark:text-white resize-none">
                {{ $note->details }}
            </textarea>
        </div>
    </div>
    <div class="action-btns text-right mt-10">
        <button type="submit" class="btn btn-dark inline-flex items-center justify-center mr-2">
            <iconify-icon class="text-xl ltr:mr-2 rtl:ml-2" icon="lucide:check"></iconify-icon>
            {{ __('Save Changes') }}
        </button>
        <a href="#" class="btn btn-danger inline-flex items-center justify-center" data-bs-dismiss="modal" aria-label="Close">
            <iconify-icon class="text-xl ltr:mr-2 rtl:ml-2" icon="lucide:x"></iconify-icon>
            {{ __('Close') }}
        </a>
    </div>
</form>
