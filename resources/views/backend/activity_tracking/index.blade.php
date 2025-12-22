@extends('backend.layouts.app')
@section('title')
    {{ __('Activity Tracking') }}
@endsection
@section('content')
    <div class="pageTitle flex justify-between flex-wrap items-center mb-6">
        <h4 class="font-medium text-xl capitalize text-slate-500 dark:text-slate-400 inline-block ltr:pr-4 rtl:pl-4 mb-1 sm:mb-0">
            @yield('title')
        </h4>
    </div>
    
    <div class="innerMenu card p-6 mb-5">
        <ul class="nav nav-pills flex items-center overflow-x-auto list-none pl-0 pb-1 md:pb-0 gap-4 menu-open w-full">
            <li class="nav-item">
                <a href="{{ route('admin.activity-logs.index') }}" class="nav-link block font-medium font-Inter text-xs leading-tight capitalize text-nowrap rounded-md px-5 py-2 focus:outline-none focus:ring-0 dark:bg-slate-900 dark:text-slate-300 {{ isActive('admin.activity-logs.index') }}">
                    {{ __('All Activities') }}
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('admin.activity-logs.users') }}" class="nav-link block font-medium font-Inter text-xs leading-tight capitalize text-nowrap rounded-md px-5 py-2 focus:outline-none focus:ring-0 dark:bg-slate-900 dark:text-slate-300 {{ isActive('admin.activity-logs.users') }}">
                    {{ __('User Activities') }}
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('admin.activity-logs.staff') }}" class="nav-link block font-medium font-Inter text-xs leading-tight capitalize text-nowrap rounded-md px-5 py-2 focus:outline-none focus:ring-0 dark:bg-slate-900 dark:text-slate-300 {{ isActive('admin.activity-logs.staff') }}">
                    {{ __('Staff Activities') }}
                </a>
            </li>
            <li class="nav-item !ml-auto">
                <a href="javascript:;" class="nav-link block font-medium font-Inter text-xs leading-tight capitalize rounded-md px-5 py-2 focus:outline-none focus:ring-0 dark:bg-slate-900 dark:text-slate-300 filter-toggle-btn">
                    <span class="flex items-center">
                        <span>{{ __('More') }}</span>
                        <iconify-icon icon="lucide:chevron-down" class="text-base ltr:ml-2 rtl:mr-2 font-light"></iconify-icon>
                    </span>
                </a>
            </li>
        </ul>

        <div class="hidden mt-5" id="filters_div">
            @yield('filters')
        </div>
    </div>
    
    @yield('activity-content')

    <!-- Modal for activity details -->
     @include('backend.activity_tracking.__activity_details')

@endsection

@section('script')

    @yield('activity-script')
    <script>
        $('body').on('click', '.view-activity', function () {
            let id = $(this).data("id");

            $.ajax({
                url: "{{ route('admin.activity-logs.show', ['id' => ':id']) }}".replace(':id', id),
                type: "GET",
                data: { id: id },
                success: function (res) {
                    $("#act_action").text(res.action);
                    $("#act_description").text(res.description ?? '-');
                    $("#act_ip").text(res.ip);
                    $("#act_agent").text(res.agent);
                    $("#act_location").text(res.location ?? '-');
                    $("#act_datetime").text(res.created_at);

                    let metaHtml = '';

                    if (res.meta && typeof res.meta === 'object' && Object.keys(res.meta).length > 0) {
                        metaHtml += '<div class="flex flex-col gap-2">';
                        $.each(res.meta, function (key, value) {
                            metaHtml += `
                                <div class="flex flex-wrap justify-between border-b border-slate-200 dark:border-slate-700 last:border-b-0 px-2 py-1 gap-3">
                                    <span class="font-medium text-slate-900 dark:text-white text-sm">${key}</span>
                                    <span class="text-slate-600 dark:text-slate-200 text-xs">${value ?? '-'}</span>
                                </div>
                            `;
                        });
                        metaHtml += '</div>';
                    } else {
                        metaHtml = '<span class="text-muted">-</span>';
                    }

                    $("#act_meta").html(metaHtml);

                    $("#activityDetailsModal").modal("show");
                }
            });
        });

        $(document).ready(function() {
            $('.filter-toggle-btn').click(function() {
                const $content = $('#filters_div');

                if ($content.hasClass('hidden')) {
                    $content.removeClass('hidden').slideDown();
                } else {
                    $content.slideUp(function() {
                        $content.addClass('hidden');
                    });
                }
            });
        });
    </script>
@endsection