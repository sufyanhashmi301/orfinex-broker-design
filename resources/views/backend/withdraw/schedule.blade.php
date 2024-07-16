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
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        @foreach($schedules as $schedule)
                            <div class="input-area flex items-center justify-between border border-slate-100 dark:border-slate-700 rounded px-3 py-2">
                                <label class="form-label !mb-0">
                                    {{ $schedule->name }}
                                </label>
                                <div class="form-switch ps-0 leading-[0]">
                                    <input 
                                        class="form-check-input" 
                                        type="hidden" 
                                        value="0" 
                                        name="{{$schedule->name}}"
                                    >
                                    <label class="relative inline-flex h-6 w-[46px] items-center rounded-full transition-all duration-150 cursor-pointer">
                                        <input 
                                            type="checkbox" 
                                            name="{{$schedule->name}}" 
                                            value="1" 
                                            @if($schedule->status) checked @endif 
                                            class="sr-only peer"
                                        >
                                        <span class="w-11 h-6 bg-gray-200 peer-focus:outline-none ring-0 rounded-full peer dark:bg-gray-900 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-black-500"></span>
                                    </label>
                                </div>
                            </div>
                        @endforeach

                    </div>

                    <div class="mt-10">
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
