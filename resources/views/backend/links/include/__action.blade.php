<div class="flex space-x-3 rtl:space-x-reverse">
    @canany(['platform-link-edit', 'document-link-edit'])
    <button type="button" class="action-btn editBtn" data-id="{{ $id }}">
        <iconify-icon icon="lucide:edit-3"></iconify-icon>
    </button>
    @endcanany
    
    @canany(['platform-link-delete', 'document-link-delete'])
    <button type="button" class="action-btn deleteBtn" data-id="{{ $id }}">
        <iconify-icon icon="lucide:trash"></iconify-icon>
    </button>
    @endcanany
</div>
