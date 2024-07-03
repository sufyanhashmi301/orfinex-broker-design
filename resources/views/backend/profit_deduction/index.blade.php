@extends('backend.layouts.app')
@section('title')
    {{ __('Manage Profit deduction') }}
@endsection
@section('style')
    <style >
        .fc-title{
            color: #ffffff;
        }
    </style>
@endsection
@section('content')
    <div class="card">
        <div class="card-header">
            <h4 class="card-title">{{ __('All Account Type') }}</h4>
            {{-- @can('schema-create')--}}
            {{-- <a href="{{route('admin.accountType.create')}}" class="btn btn-dark btn-sm inline-flex items-center justify-center">--}}
            {{-- <iconify-icon class="text-lg ltr:mr-2 rtl:ml-2" icon="lucide:plus-circle"></iconify-icon>{{ __('Add New') }}</a>--}}
            {{-- @endcan--}}
        </div>
        <div class="card-body p-6">
            <div id='full-calander-active'></div>
        </div>
    </div>
@endsection
@section('script')
    <script>
        $(document).ready(function () {

            var SITEURL = "{{ url('/') }}";
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            var calendar = $('#full-calander-active').fullCalendar({
                editable: true,
                validRange: function(nowDate) {
                    return {
                        start: nowDate,
                        end: nowDate.clone().add(12, 'months')
                    };
                },
                events:  [
                        @foreach($profits as $profit)
                    {
                        id: '{{ $profit->id }}',
                        title : '{{ $profit->percentage }}%',
                        start : '{{ $profit->start_date }}',
                    },
                    @endforeach
                ],
                // eventColor: '#378006',
                editable: true,
                displayEventTime: false,
                selectable: true,
                selectHelper: true,
                select: function (start, end, allDay) {
                    var title = prompt('Add Percentage:');
                    if (title) {
                        var start_date = $.fullCalendar.formatDate(start, "Y-MM-DD");
                        var end_date = $.fullCalendar.formatDate(end, "Y-MM-DD");
                        $.ajax({
                            url: SITEURL + "/admin/profit/deduction/store",
                            data: {
                                percentage: title,
                                start_date: start_date,
                                end_date: end_date,
                                type: 'add'
                            },
                            type: "POST",
                            success: function (data) {
                                if(data.errors){
                                    displayMessageError(data.errors.percentage[0]);
                                }else {
                                    displayMessage("Added Successfully");
                                    setTimeout(function() {
                                        location.reload();
                                    }, 1000);
                                }
                            }
                        });
                    }
                },
                eventDrop: function (event, delta) {
                    var start_date = moment(event.start).format('Y-MM-DD');
                    var end_date = moment(event.end).format('Y-MM-DD');

                    $.ajax({
                        url: SITEURL + '/admin/profit/deduction/store',
                        data: {
                            // title: event.percentage,
                            start_date: start_date,
                            end_date: end_date,
                            id: event.id,
                            type: 'update'
                        },
                        type: "POST",
                        success: function (response) {
                            displayMessage("Updated Successfully");
                        }
                    });
                },
                eventClick: function (event) {
                    // console.log(event);
                    var deleteMsg = confirm("Do you really want to delete?");
                    if (deleteMsg) {
                        $.ajax({
                            type: "POST",
                            url: SITEURL + '/admin/profit/deduction/store',
                            data: {
                                id: event.id,
                                type: 'delete'
                            },
                            success: function (response) {
                                calendar.fullCalendar('removeEvents', event.id);
                                displayMessage("Deleted Successfully");
                            }
                        });
                    }
                }
            });
        });

        function displayMessage(message) {
            toastr.success(message, 'Percentage');
        }
        function displayMessageError(message) {
            toastr.error(message, 'Percentage');
        }

    </script>
@endsection
