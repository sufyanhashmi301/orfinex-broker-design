<div class="flex space-x-3 rtl:space-x-reverse">
    @can('customer-edit')
        <a href="{{route('admin.user.edit',$id)}}" class="toolTip onTop action-btn" data-tippy-theme="dark" data-tippy-content="Edit User">
            <iconify-icon icon="lucide:edit-3"></iconify-icon>
        </a>
    @endcan
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
        <div class="flex space-x-3 rtl:space-x-reverse">
            @can('customer-change-password')
                <button
                    type="button"
                    class="toolTip onTop action-btn reset-password-btn"
                    data-id="{{ $id }}"
                    data-name="{{ $first_name.' '.$last_name }}"
                    data-email="{{ $email }}"
                    data-tippy-theme="dark"
                    data-tippy-content="Reset Password">
                    <iconify-icon icon="lucide:lock"></iconify-icon>
                </button>
            @endcan
        </div>

</div>

<script>
    $(document).ajaxComplete(function () {
        "use strict";
        $('.toolTip').tooltip({
            "html": true,
        });
    });
</script>
