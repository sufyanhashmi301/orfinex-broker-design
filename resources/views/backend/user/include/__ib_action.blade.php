
@can('customer-mail-send')
    <span type="button"
        data-id="{{$id}}"
        class="send-mail"
    >
    <button class="action-btn" data-bs-toggle="tooltip" title="Send Email" data-bs-original-title="Send Email">
        <iconify-icon icon="lucide:mail"></iconify-icon>
    </button>
</span>
@endcan

<script>
    $(document).ajaxComplete(function () {
        "use strict";
        $('[data-bs-toggle="tooltip"]').tooltip({
            "html": true,
        });
    });
</script>
