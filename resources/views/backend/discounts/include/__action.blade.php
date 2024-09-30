<div class="action-buttons flex space-x-3 rtl:space-x-reverse">
    <!-- Edit Button -->
    <a href="javascript:void(0)" data-id="{{ $id }}" class="edit-btn action-btn" data-bs-toggle="modal" data-bs-target="#editDiscountModal" onclick="editDiscount({{ $id }})">
        <iconify-icon icon="lucide:edit-3"></iconify-icon>
    </a>

    <!-- Delete Button -->
    <a href="javascript:void(0)" data-id="{{ $id }}" data-name="{{ $code_name }}" class="delete-btn action-btn" data-bs-toggle="modal" data-bs-target="#deleteDiscountModal" onclick="deleteDiscount({{ $id }}, '{{ $code_name }}')">
        <iconify-icon icon="lucide:trash"></iconify-icon>
    </a>
</div>
