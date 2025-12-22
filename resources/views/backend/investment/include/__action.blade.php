@php
    $isReviewList = in_array($status ?? '', [\App\Enums\ForexAccountStatus::Pending, \App\Enums\ForexAccountStatus::Canceled]);
    $metaArray = [];
    if(isset($meta)){
        $metaArray = is_array($meta) ? $meta : (json_decode($meta, true) ?: []);
    }
    $storedComment = $metaArray['last_action_comment'] ?? '';
@endphp
<div class="flex space-x-3 rtl:space-x-reverse">
    @if($isReviewList)
        <a href="javascript:;" class="action-btn open-account-action-modal" data-id="{{ $id }}" data-login="{{ $login }}" data-status="{{ $status }}" data-account_type="{{ $account_type }}" data-group="{{ $group ?? '' }}" data-username="{{ $username_sort ?? ($username ?? '') }}" data-email="{{ $user->email ?? '' }}" data-comment="{{ $storedComment }}">
            <iconify-icon icon="mdi:eye"></iconify-icon>
        </a>
    @else
    @can('accounts-trades-view')
        <a href="javascript:;" class="action-btn open-trades-modal" data-login="{{ $login }}">
            <iconify-icon icon="fluent:apps-list-24-filled"></iconify-icon>
        </a>
    @endcan
    @if(in_array($status ?? '', [\App\Enums\ForexAccountStatus::Ongoing, \App\Enums\ForexAccountStatus::Archive]))
        <a href="javascript:;" class="action-btn open-delete-account-modal toolTip onTop" data-tippy-theme="dark" data-tippy-content="{{ __('Delete (Soft)') }}" data-login="{{ $login }}">
            <iconify-icon icon="lucide:trash-2"></iconify-icon>
        </a>
    @endif
    @can('accounts-action')
    <a href="{{route('admin.user.edit',$user_id)}}" class="toolTip onTop action-btn" data-tippy-theme="dark" data-tippy-content="Edit User">
        <iconify-icon icon="lucide:edit-3"></iconify-icon>
    </a>
    <div class="dropdown relative">
        <button class="action-btn" type="button" data-bs-toggle="dropdown" aria-expanded="false">
            <iconify-icon icon="heroicons-outline:dots-vertical"></iconify-icon>
        </button>
        <ul class=" dropdown-menu w-max absolute text-sm text-slate-700 dark:text-white hidden bg-white shadow z-[2] overflow-hidden list-none text-left rounded-lg mt-1 m-0 bg-clip-padding border-none">
            <li>
                <a class="text-slate-600 dark:text-white block font-Inter font-normal px-4 py-2 hover:bg-slate-100 dark:hover:bg-slate-600 dark:hover:text-white dropdown-account-info"
                   href=""
                   type="button"
                   data-bs-toggle="modal"
                   data-bs-target="#accountDetails"
                   data-login="{{ $login }}"
                   data-account_name="{{ $account_name }}"
                   data-server="{{ $server }}"
                   data-schema_title="{{ $schema['title'] }}"
                   data-account_type="{{ $account_type }}"
                   data-leverage="{{ $leverage }}"
                   data-balance="{{ get_mt5_account_balance($login) }}"
                   data-free-margin="{{ $free_margin }}"
                   data-equity="{{ get_mt5_account_equity($login) }}">{{ __('Account Details') }}</a>
            </li>
            <li>
                <a class="text-slate-600 dark:text-white block font-Inter font-normal px-4 py-2 hover:bg-slate-100 dark:hover:bg-slate-600 dark:hover:text-white dropdown-update-type"
                   href="javascript:void(0);"
                   type="button"
                   data-bs-toggle="modal"
                   data-bs-target="#changeForexSchema"
                   data-login="{{ $login }}"
                   data-forex_schema_id="{{ $forex_schema_id }}"
                   data-action="{{ route('admin.forex.get.schema') }}">{{ __('Change Account Schema') }}</a>
            </li>
            <li>
                <a class="text-slate-600 dark:text-white block font-Inter font-normal px-4 py-2 hover:bg-slate-100 dark:hover:bg-slate-600 dark:hover:text-white dropdown-update-account-type"
                   href="javascript:void(0);"
                   type="button"
                   data-bs-toggle="modal"
                   data-bs-target="#changeAccountType"
                   data-login="{{ $login }}"
                   data-account_type="{{ $account_type }}">{{ __('Change Account Type') }}</a>
            </li>
            
            <li>
                <a class="text-slate-600 dark:text-white block font-Inter font-normal px-4 py-2 hover:bg-slate-100 dark:hover:bg-slate-600 dark:hover:text-white dropdown-update-leverage"
                   href=""
                   type="button"
                   data-bs-toggle="modal"
                   data-bs-target="#changeLeverage"
                   data-login="{{ $login }}"
                   data-id="{{ $id }}"
                   data-user-id="{{ $user_id }}"
                   data-action="{{route('admin.forex.get.leverage')}}">{{ __('Change leverage') }}</a>
            </li>
            <li>
                <a class="text-slate-600 dark:text-white block font-Inter font-normal px-4 py-2 hover:bg-slate-100 dark:hover:bg-slate-600 dark:hover:text-white dropdown-update-name"
                   href=""
                   type="button"
                   data-bs-toggle="modal"
                   data-bs-target="#accountRename" data-login="{{ $login }}"
                   data-account_name="{{ $account_name }}">{{ __('Rename account') }}</a>
            </li>
            <li>
                <a class="text-slate-600 dark:text-white block font-Inter font-normal px-4 py-2 hover:bg-slate-100 dark:hover:bg-slate-600 dark:hover:text-white dropdown-update-password"
                   href=""
                   type="button"
                   data-bs-toggle="modal"
                   data-bs-target="#changeAccountPass"
                   data-login="{{ $login }}">{{ __('Change trading password') }}</a>
            </li>
            <li>
                <a class="text-slate-600 dark:text-white block font-Inter font-normal px-4 py-2 hover:bg-slate-100 dark:hover:bg-slate-600 dark:hover:text-white dropdown-update-password"
                   href=""
                   type="button"
                   data-bs-toggle="modal"
                   data-bs-target="#changeInvestorPass" data-login="{{ $login }}">{{ __('Change investor password') }}</a>
            </li>
            
            <li>
                @if($status == \App\Enums\ForexAccountStatus::Archive)
                    <a class="text-slate-600 dark:text-white block font-Inter font-normal px-4 py-2 hover:bg-slate-100 dark:hover:bg-slate-600 dark:hover:text-white archive-login" href=""
                       type="button"
                       data-bs-toggle="modal"
                       data-login="{{ $login }}"
                       data-bs-target="#unarchiveAccount">{{ __('Unarchive account') }}</a>
                @else
                    <a class="text-slate-600 dark:text-white block font-Inter font-normal px-4 py-2 hover:bg-slate-100 dark:hover:bg-slate-600 dark:hover:text-white archive-login"
                       href=""
                       type="button"
                       data-bs-toggle="modal"
                       data-login="{{ $login }}"
                       data-bs-target="#archiveAccount">{{ __('Archive account') }}</a>
                @endif
            </li>
            <li>
                <a class="text-slate-600 dark:text-white block font-Inter font-normal px-4 py-2 hover:bg-slate-100 dark:hover:bg-slate-600 dark:hover:text-white send-statement-btn"
                   href="javascript:void(0)"
                   data-login="{{ $login }}"
                   onclick="sendStatement('{{ $login }}')">{{ __('Send Statement Email') }}</a>
            </li>
        </ul>
    </div>
    @endcan
    @endif
</div>

