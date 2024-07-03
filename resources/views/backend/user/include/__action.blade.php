<div class="flex space-x-3 rtl:space-x-reverse">
    @canany(['customer-basic-manage','customer-balance-add-or-subtract','customer-change-password','all-type-status'])
        <a href="{{route('admin.user.edit',$id)}}" class="toolTip onTop action-btn" data-tippy-theme="dark" data-tippy-content="Edit User">
            <iconify-icon icon="lucide:edit-3"></iconify-icon>
        </a>
    @endcanany
    @can('customer-mail-send')
        <span type="button"
            data-id="{{$id}}"
            data-name="{{ $first_name.' '. $last_name }}"
            class="send-mail"
        >
            <button class="toolTip onTop action-btn" data-tippy-theme="dark" data-tippy-content="Send Email">
                <iconify-icon icon="lucide:mail"></iconify-icon>
            </button>
        </span>
    @endcan
</div>

<script>
    $(document).ajaxComplete(function () {
        "use strict";
        $('.toolTip').tooltip({
            "html": true,
        });
    });
</script>
