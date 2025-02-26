<div class="flex space-x-3 rtl:space-x-reverse">
    <!-- Update your __action Blade file -->
    @if ($user->ibQuestionAnswers)
        <button type="button" class="action-btn detail-btn" data-toggle="modal" data-target="#viewDataModal" data-user-id="{{ $user->id }}">
            <iconify-icon icon="lucide:eye"></iconify-icon>
        </button>

    @else
        <button type="button" class="toolTip onTop action-btn detail-btn" data-tippy-theme="dark" data-tippy-content="No IB Data Available" title="No IB Data Available">
            <iconify-icon icon="lucide:eye-off"></iconify-icon>
        </button>
    @endif


    @can('ib-action')
{{--        @if($user->ib_status !=='approved')--}}
        <!-- Edit Button -->
            <button type="button" class="toolTip onTop action-btn edit-btn"
                    data-tippy-theme="dark"
                    data-tippy-content="Edit"
                    title="Edit"
                    data-user-id="{{ $user->id }}"
                    data-full-name="{{ $user->full_name }}"
                    data-ib-group-id="{{ $user->ib_group_id }}"
                    data-bs-toggle="modal"
                    data-bs-target="#addIBModal">
                <iconify-icon icon="lucide:edit-3"></iconify-icon>
            </button>



            <button type="button" class="toolTip onTop action-btn reject-btn" data-tippy-theme="dark" data-tippy-content="Reject" title="Reject">
                <iconify-icon icon="mdi:multiply"></iconify-icon>
            </button>
        @endif
{{--    @endcan--}}
</div>
