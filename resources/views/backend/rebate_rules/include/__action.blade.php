<div class="flex space-x-3 rtl:space-x-reverse">
    @can('rebate-rules-edit')
    <a href="{{ route('admin.rebate-rules.edit',$id) }}" data-id="{{ $id }}" class="action-btn editRebateRule">
        <iconify-icon icon="lucide:edit-3"></iconify-icon>
    </a>
    @endcan
    @can('rebate-rules-delete')
    <button type="button" data-id="{{ $id }}"  class="action-btn deleteRebateRule">
        <iconify-icon icon="lucide:trash-2"></iconify-icon>
     </button>
     @endcan
</div>
