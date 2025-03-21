@extends('backend.layouts.app')
@section('title')
    {{ __('Attach User') }}
@endsection
@section('content')
    <div class="pageTitle flex justify-between flex-wrap items-center mb-6">
        <h4 class="font-medium text-xl capitalize text-slate-500 dark:text-slate-400 inline-block ltr:pr-4 rtl:pl-4 mb-1 sm:mb-0">
            {{ __('Attach Users') }}
        </h4>
    </div>
    <div class="grid grid-cols-12 gap-5">
        <div class="lg:col-span-5 col-span-12">
            <div class="card">
                <div class="card-header noborder">
                    <h4 class="card-title">{{ __('Attach User') }}</h4>
                </div>
                <div class="card-body p-6 pt-3">
                    <form action="{{ route('admin.staff.attachUser', $staff->id) }}" method="post">
                        @csrf
                        <div class="space-y-5">
                            <div class="input-area">
                                <label class="form-label">{{ __('IB Groups:') }}</label>
                                <select name="ib_groups[]" class="select2 form-control w-full" data-placeholder="Select Options" multiple>
                                    <option value="all" @if(in_array('all', $staff->ib_groups ?? [])) selected @endif>
                                        {{ __('All') }}
                                    </option>
                                    @foreach($ibGroups as $ibGroup)
                                        <option value="{{ $ibGroup->id }}"
                                            {{ in_array($ibGroup->id, $staff->ib_groups ?? []) ? 'selected' : '' }}>
                                            {{ $ibGroup->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="input-area">
                                <label class="form-label">{{ __('Account Types:') }}</label>
                                <select name="account_types[]" class="select2 form-control w-full" data-placeholder="Select Options" multiple>
                                    <option value="all" @if(in_array('all', $staff->account_types ?? [])) selected @endif>
                                        {{ __('All') }}
                                    </option>
                                    @foreach($schemas as $schema)
                                        <option value="{{ $schema->id }}"
                                            {{ in_array($schema->id, $staff->account_types ?? []) ? 'selected' : '' }}>
                                            {{ $schema->title }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="input-area">
                                <label class="form-label">{{ __('Attach Users:') }}</label>
                                <select name="user_ids[]" id="users_input" class="form-control w-full" data-placeholder="Select Options" multiple></select>
                            </div>
                        </div>
                        <div class="action-btns text-right mt-10">
                            <button type="submit" class="btn btn-dark inline-flex items-center justify-center" id="update-staff__btn">
                                <iconify-icon class="text-xl ltr:mr-2 rtl:ml-2" icon="lucide:check"></iconify-icon>
                                {{ __('Save Changes') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="lg:col-span-7 col-span-12">
            <div class="card h-full">
                <div class="card-body relative px-6">
                    <div class="overflow-x-auto -mx-6 dashcode-data-table">
                        <span class="col-span-8 hidden"></span>
                        <span class="col-span-4 hidden"></span>
                        <div class="inline-block min-w-full align-middle">
                            <div class="overflow-hidden ">
                                <table class="min-w-full divide-y divide-slate-100 dark:divide-slate-700" id="attachedUsers">
                                    <thead class="bg-slate-200 dark:bg-slate-700">
                                        <tr>
                                            <th scope="col" class="table-th">{{ __('User') }}</th>
                                            <th scope="col" class="table-th">{{ __('Email') }}</th>
                                            <th scope="col" class="table-th">{{ __('Actions') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-slate-100 dark:divide-slate-700">

                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div id="processingIndicator" class="text-center">
                        {{-- <img src="{{ asset('global/images/loading.gif') }}" class="inline-block h-20" alt="Loader"> --}}
                        <iconify-icon class="spining-icon text-5xl dark:text-slate-100" icon="lucide:loader"></iconify-icon>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Detach User Modal--}}
    @include('backend.staff.modal.__detach_user')

@endsection
@section('script')
    <script>
        (function ($) {
            "use strict";

            var table = $('#attachedUsers')
            .on('processing.dt', function (e, settings, processing) {
                $('#processingIndicator').css('display', processing ? 'block' : 'none');
            }).DataTable({
                dom: "<'grid grid-cols-12 gap-5 px-6 mt-6'<'col-span-4'l><'col-span-8 flex justify-end'f><'#pagination.flex items-center'>><'min-w-full't><'flex justify-end items-center border-t border-slate-100 dark:border-slate-700 gap-3 px-4 py-5 mt-auto'p>",
                paging: true,
                ordering: true,
                lengthChange: false,
                info: true,
                searching: true,
                language: {
                    lengthMenu: "Show _MENU_ entries",
                    info: "Showing _START_ to _END_ of _TOTAL_ entries",
                    paginate: {
                        previous: "<iconify-icon icon=\"ic:round-keyboard-arrow-left\"></iconify-icon>",
                        next: "<iconify-icon icon=\"ic:round-keyboard-arrow-right\"></iconify-icon>"
                    },
                    search: "Search:"
                },
                serverSide: true,
                autoWidth: false,
                ajax: {
                    url: "{{ route('admin.staff.attachedUsers', $staff->id) }}",
                    data: function (d) {
                        d.global_search = $('#global_search').val();
                    }
                },
                columns: [
                    { data: 'full_name', name: 'full_name' },
                    { data: 'email', name: 'email' },
                    { data: 'action', name: 'action', orderable: false, searchable: false },
                ]
            });

            $('#global_search').keyup(function() {
                table.draw();
            });

        })(jQuery);

        $('#users_input').select2({
            ajax: {
                url: '{{ route("admin.user.search") }}',
                dataType: 'json',
                delay: 250,
                processResults: function (data) {
                    return {
                        results: data.results.map(function(item) {
                            return {
                                id: item.id,
                                text: item.text + ' (' + item.email + ')',
                                email: item.email
                            };
                        })
                    };
                },
                cache: true
            },
            templateResult: function(data) {
                return $('<span>' + data.text + '</span>');
            },
            templateSelection: function(data) {
                return data.text;
            }
        });

        $('body').on('click', '.userDetachBtn', function (e) {
            e.preventDefault();
            let userId = $(this).data('user-id');
            let staffId = $(this).data('staff-id');
            var name = $(this).data('name');

            var url = '{{ route("admin.staff.detachUser", ":staffId") }}';
            url = url.replace(':staffId', staffId);
            $('#detachUserForm').attr('action', url);

            $('#userIdInput').val(userId);
            $('.name').html(name);
            $('#detachUserModal').modal('show');
        });

    </script>
@endsection
