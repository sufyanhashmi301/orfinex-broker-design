@extends('backend.layouts.app')
@section('title')
    {{ __('Schedule') }}
@endsection
@section('content')
    <div class="card">
        <div class="card-header noborder">
            <h4 class="card-title">{{ __('All Schedules') }}</h4>
            <a
                href=""
                class="btn btn-dark btn-sm inline-flex items-center justify-center"
                type="button"
                data-bs-toggle="modal"
                data-bs-target="#addNewSchedule"
            >
                <iconify-icon class="text-lg ltr:mr-2 rtl:ml-2" icon="lucide:plus-circle"></iconify-icon>
                {{ __('Add New') }}
            </a>
        </div>
        <div class="card-body px-6 pb-6">
            <div class="overflow-x-auto -mx-6">
                <div class="inline-block min-w-full align-middle">
                    <div class="overflow-hidden ">
                        <table class="min-w-full divide-y divide-slate-100 table-fixed dark:divide-slate-700">
                            <thead class="border-t border-slate-100 dark:border-slate-800">
                                <tr>
                                    <th scope="col" class="table-th">{{ __('#') }}</th>
                                    <th scope="col" class="table-th">{{ __('Name') }}</th>
                                    <th scope="col" class="table-th">{{ __('Time') }}</th>
                                    <th scope="col" class="table-th">{{ __('Action') }}</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-slate-100 dark:bg-slate-800 dark:divide-slate-700">
                                @foreach($schedules as $schedule)
                                <tr>
                                    <td class="table-td">{{++$loop->index}}</td>
                                    <td class="table-td">{{$schedule->name}}</td>
                                    <td class="table-td">
                                        <strong>{{$schedule->time}} @php echo $schedule->time > 1 ? 'Hours' : 'Hour' @endphp</strong>
                                    </td>
                                    <td class="table-td">
                                        <button
                                            class="action-btn"
                                            type="button"
                                            id="edit"
                                            data-id="{{$schedule->id}}"
                                        >
                                            <iconify-icon icon="lucide:edit-3"></iconify-icon>
                                        </button>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal for Add New Schedule -->
    @include('backend.schedule.modal.__new_schedule')
    <!-- Modal for Add New Schedule-->

    <!-- Modal for Edit Schedule -->
    @include('backend.schedule.modal.__edit_schedule')
    <!-- Modal for Edit Schedule-->
    
@endsection

@section('script')
    <script>

        $('body').on('click', '#edit', function (event) {
            "use strict";
            event.preventDefault();
            var id = $(this).data('id');
            $.get('schedule/' + id + '/edit', function (data) {

                var url = '{{ route("admin.schedule.update", ":id") }}';
                url = url.replace(':id', id);
                $('#editForm').attr('action', url)
                $('#editModal').modal('show');

                $('#name').val(data.name);
                $('#time').val(data.time);
                if (data.time <= 1) {
                    $('#time-level').html('Hour');
                } else {
                    $('#time-level').html('Hours');
                }
            })
        });

    </script>
@endsection
