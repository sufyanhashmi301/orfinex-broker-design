@can('language-action')
<div class="flex space-x-3 rtl:space-x-reverse">
    <a href="{{ route('admin.language-keyword',$locale) }}" class="action-btn" data-bs-toggle="tooltip"
        title="" data-bs-original-title="Change Value">
        <iconify-icon icon="lucide:languages"></iconify-icon>
    </a>

    <a href="{{ route('admin.language.edit',$id) }}" class="action-btn" data-bs-toggle="tooltip"
        title="" data-bs-original-title="Edit Language">
        <iconify-icon icon="lucide:edit-3"></iconify-icon>
    </a>
    @if($locale != 'en')
        <span type="button" id="deleteLanguageModal" data-id="{{$id}}" data-name="{{$name}}">
        <button class="action-btn" data-bs-toggle="tooltip" title="Delete Language"
            data-bs-original-title="Delete Language">
            <iconify-icon icon="lucide:trash-2"></iconify-icon>
        </button>
    </span>
    @endif
</div>
@endcan