@extends('backend.setting.promotion.index')
@section('title')
    {{ __('Slider Settings') }}
@endsection
@section('content')
    <div class="flex justify-between flex-wrap items-center mb-6">
        <h4 class="font-medium text-xl capitalize text-slate-500 dark:text-slate-400 inline-block ltr:pr-4 rtl:pl-4 mb-1 sm:mb-0">
            @yield('title')
        </h4>

        <a href="javascript:void(0)" data-bs-target="#new-slider-modal" data-bs-toggle="modal" class="btn btn-primary" >
            <iconify-icon class="text-xl ltr:mr-2 rtl:ml-2" style="position: relative; top: 4px" icon="lucide:plus"></iconify-icon>
            {{ __('Create New') }} 
        </a>
    </div>
    <div class="card p-4 mb-5">
        <ul class="nav nav-pills flex items-center flex-wrap list-none pl-0 space-x-4 menu-open">
            <li class="nav-item">
                <a href="javascript:;" class="nav-link block font-medium font-Inter text-sm leading-tight capitalize rounded-md px-6 py-3 focus:outline-none focus:ring-0 dark:bg-slate-900 dark:text-slate-300 active">
                    {{ __('User Dashboard') }}
                </a>
            </li>

            <li class="nav-item">
                <a href="javascript:void(0);" class="nav-link block font-medium font-Inter text-sm leading-tight capitalize rounded-md px-6 py-3 focus:outline-none focus:ring-0 dark:bg-slate-900 dark:text-slate-300">
                    {{ __('Coming Soon') }}
                </a>
            </li>


        </ul>
    </div>

    @if (count($sliders) > 0)
        <div class="grid grid-cols-3 gap-5">
            
            @foreach ($sliders as $slider)
                <div class="card">
                    <div class="card-header noborder" style="padding-bottom: 0px">
                        <h3 class="text-lg text-slate-900 dark:text-white font-medium capitalize">{{ $slider->name }}</h3>

                        <div class="badge badge-{{ $slider->status == 'enabled' ? 'success' : 'danger' }}">{{ $slider->status == 'enabled' ? 'Active' : 'Inactive' }}</div>
                    </div>
                    <div class="card-body mt-4 p-6 pt-0">
                        <div class="slider carousel-interval owl-carousel">
                            @foreach ($slider->slides as $slide)
                                <img class="w-full" src="{{ asset($slide) }}" alt="image">
                            @endforeach
                        </div>
                    </div>

                    <form action="{{ route('admin.slider.destroy', ['id' => $slider->id]) }}" method="POST">
                        @method('DELETE')
                        @csrf
                        <button type="submit" class="btn btn-outline-danger float-right" style="margin-right: 10px; margin-bottom: 10px;border-width: 2px; padding-bottom: 9px; min-width: 0">Delete</button>
                    </form>
                    @if ($slider->status == 'disabled')
                        <a href="{{ route('admin.slider.update', ['id' => $slider->id, 'action' => 'activate']) }}" class="btn btn-primary float-right" style="margin-right: 10px; margin-bottom: 10px;min-width: 0">Activate</a>
                    @else
                        <a href="{{ route('admin.slider.update', ['id' => $slider->id, 'action' => 'deactivate']) }}" class="btn btn-primary float-right" style="margin-right: 10px; margin-bottom: 10px;min-width: 0">Deactivate</a>
                    @endif
                </div>
            @endforeach


        </div>
    @else
        <div class="card basicTable_wrapper items-center justify-center py-10 px-10">
            <div class="flex items-center justify-center flex-col gap-3">
                <img src="{{ asset('frontend/images/icon/danger.png') }}" alt="">
                <p class="text-lg text-center text-slate-600 dark:text-slate-100 mb-3">
                    {{ __("Nothing to see here.") }}
                </p>
            </div>
        </div>
    @endif
    

    @include('backend.setting.promotion.slider.includes.__new_slider_modal')

@endsection