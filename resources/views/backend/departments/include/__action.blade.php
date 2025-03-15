<div class="flex space-x-3 rtl:space-x-reverse">
  @can('department-edit')
    <a href="{{ route('admin.departments.edit',$id) }}" data-id="{{ $id }}" class="action-btn editDepartment">
      <iconify-icon icon="lucide:edit-3"></iconify-icon>
    </a>
  @endcan

  @can('department-delete')
    <button type="button" data-id="{{ $id }}"  class="action-btn deleteDepartment">
      <iconify-icon icon="lucide:trash-2"></iconify-icon>
    </button>
  @endcan
</div>
