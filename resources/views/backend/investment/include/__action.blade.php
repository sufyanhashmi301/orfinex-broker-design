@can('accounts-action')
<div class="flex space-x-3 rtl:space-x-reverse">
    <a href="javascript:;" class="action-btn open-trades-modal" data-login="{{ $login }}">
        <iconify-icon icon="fluent:apps-list-24-filled"></iconify-icon>
    </a>
    <a href="{{ route('admin.user.edit', $user_id) }}" class="toolTip onTop action-btn" data-tippy-theme="dark" data-tippy-content="Edit User">
        <iconify-icon icon="lucide:edit-3"></iconify-icon>
    </a>
    <a href="javascript:;" class="action-btn reset-data-btn" data-id="{{ $login }}">
        <iconify-icon icon="material-symbols:restart-alt-rounded"></iconify-icon>
    </a>
</div>
@endcan