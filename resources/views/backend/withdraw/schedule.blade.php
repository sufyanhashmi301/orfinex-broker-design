@extends('backend.withdraw.index')
@section('title')
    {{ __('Withdraw Schedule') }}
@endsection
@section('page-title')
    <div class="flex justify-between flex-wrap items-center mb-6">
        <h4 class="font-medium text-xl capitalize text-slate-500 dark:text-slate-400 inline-block ltr:pr-4 rtl:pl-4 mb-1 sm:mb-0">
            @yield('title')
        </h4>
    </div>
@endsection
@section('withdraw_content')
    <div class="max-w-5xl mx-auto">
        <div class="card">
            <div class="card-body p-6">
                <form action="{{ route('admin.withdraw.schedule.update') }}" method="post">
                    @csrf
                    <div class="grid grid-cols-12 items-center gap-5">
                        @foreach($schedules as $schedule)
                            <div class="col-span-4 form-label pt-0">{{ $schedule->name }}</div>
                            <div class="col-span-8">
                                <div class="form-switch ps-0">
                                    <div class="switch-field flex mb-3 overflow-hidden">
                                        <input
                                            type="radio"
                                            id="active-{{$schedule->id}}"
                                            name="{{$schedule->name}}"
                                            value="1"
                                            @if($schedule->status) checked @endif
                                        />
                                        <label for="active-{{$schedule->id}}">{{ __('Enable') }}</label>
                                        <input
                                            type="radio"
                                            id="disable-{{$schedule->id}}"
                                            name="{{$schedule->name}}"
                                            value="0"
                                            @if(!$schedule->status) checked @endif
                                        />
                                        <label for="disable-{{$schedule->id}}">{{ __('Disabled') }}</label>
                                    </div>
                                </div>
                            </div>
                        @endforeach

                    </div>

                    <div class="text-right mt-5">
                        <button
                            type="submit"
                            class="btn btn-dark inline-flex items-center justify-center">
                            {{ __('Save Changes') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
