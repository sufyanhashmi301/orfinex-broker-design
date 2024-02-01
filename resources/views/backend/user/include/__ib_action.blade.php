
@can('customer-mail-send')
    <span type="button"
          data-id="{{$id}}"
          class="send-mail"
    ><button class="round-icon-btn red-btn" data-bs-toggle="tooltip" title="Send Email"
             data-bs-original-title="Send Email"><i icon-name="mail"></i></button></span>
@endcan

<script>
    lucide.createIcons();
    $(document).ajaxComplete(function () {
        "use strict";
        $('[data-bs-toggle="tooltip"]').tooltip({
            "html": true,
        });
    });
</script>
