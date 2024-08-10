<div class="flex space-x-3 rtl:space-x-reverse">
    <a href="{{ route('admin.designations.edit',$id) }}" data-id="{{ $id }}" class="action-btn editDesignation">
        <iconify-icon icon="lucide:edit-3"></iconify-icon>
    </a>
    <button type="button" data-id="{{ $id }}"  class="action-btn deleteDesignation">
        <iconify-icon icon="lucide:trash-2"></iconify-icon>
     </button>
</div>
