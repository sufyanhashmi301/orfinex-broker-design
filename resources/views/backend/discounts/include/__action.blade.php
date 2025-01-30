<div class="flex space-x-3 rtl:space-x-reverse">
    @can('discount-code-edit')
        <a href="javascript:void(0)" data-id="{{ $id }}" onclick="editDiscount({{ $id }})" data-bs-toggle="modal" data-bs-target="#editDiscountModal" class="action-btn">
            <iconify-icon icon="lucide:edit-3"></iconify-icon>
        </a>
    @endcan
    @can('discount-code-delete')
        <a href="javascript:void(0)" class="action-btn delete-schema-btn" data-id="{{ $id }}" data-name="{{ $code_name }}" data-bs-toggle="modal" data-bs-target="#deleteDiscountModal" onclick="deleteDiscount({{ $id }}, '{{ $code_name }}')">
            <iconify-icon icon="lucide:trash"></iconify-icon>
        </a>
    @endcan
</div>