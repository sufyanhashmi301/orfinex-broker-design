<div class="flex space-x-3 rtl:space-x-reverse">
    
        <a href="{{route('admin.user.edit',$user_id)}}" class="toolTip onTop action-btn" data-tippy-theme="dark" data-tippy-content="Edit User">
            <iconify-icon icon="lucide:edit-3"></iconify-icon>
        </a>

   
</div>

<script>
    $(document).ajaxComplete(function () {
        "use strict";
        $('.toolTip').tooltip({
            "html": true,
        });
    });
</script>
