<div class="flex space-x-3 rtl:space-x-reverse">
    <button class="action-btn edit-note" data-id="{{ $id }}" data-description="{{ $description }}" data-url="{{ route('admin.user.note.edit', $id) }}">
        <iconify-icon icon="lucide:edit"></iconify-icon>
    </button>
    <button class="action-btn delete-note" data-id="{{ $id }}">
        <iconify-icon icon="lucide:trash"></iconify-icon>
    </button>
</div>
