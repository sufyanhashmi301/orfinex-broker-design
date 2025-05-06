@extends('backend.links.index')
@section('title')
    {{ __('Social Links') }}
@endsection
@section('links-content')
    <?php
        $section = 'social_links';
        $fields = config('setting.social_links');
    ?>

    <style>
        svg {
            height: 24px !important;
            width: 24px !important;
        }
    </style>
    <div class="card">
        <div class="card-body p-6">
            @include('backend.setting.site_setting.include.form.__open_action')
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                @foreach($fields['elements'] as $key => $field)
                    {{-- @dd($field) --}}
                    @if($field['type'] == 'url')
                        <div class="input-area">
                            <div></div>
                            <label for="" class="form-label">{{ __($field['label']) }}</label>
                            <div class="relative">
                                <input type="{{$field['type']}}" name="{{ $field['name'] }}" class="form-control {{ $errors->has($field['name']) ? 'has-error' : '' }}" value="{{oldSetting($field['name'],$section)}}" placeholder="URL" style="padding-left: 55px" />

                                <span class="absolute left-0 top-1/2 px-3 -translate-y-1/2 h-full border-r border-r-slate-200 dark:border-r-slate-700 flex items-center justify-center">{!! isset($field['icon']) ? $field['icon'] : '' !!}</span>
                            </div>
                    
                            {{-- To show chekboxes for overall, purchase page and signup  --}}
                            @php
                                $navbar = $fields['elements'][$loop->index + 1];
                                $dashboard = $fields['elements'][$loop->index + 2];
                                $auth = $fields['elements'][$loop->index + 3];
                            @endphp
                            
                            <div class="pt-2 ml-1">
                                <input type="hidden" name="{{ $navbar['name'] }}" value="0">
                                <span class="">
                                    <label class="relative inline-flex h-6 w-[46px] items-center rounded-full transition-all duration-150 cursor-pointer">
                                        <input type="checkbox" name="{{ $navbar['name'] }}" value="1" @if(oldSetting($navbar['name'],$section)) checked @endif class="sr-only peer">
                                        <span class="w-11 h-6 bg-gray-200 peer-focus:outline-none ring-0 rounded-full peer dark:bg-gray-900 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-black-500"></span>
                                    </label>
                                </span> 
                                <small><label class="ml-2" style="display: inline; position: relative; top: -7px" >On Navbar Page</label></small>
                            </div>
                            <div class="pt-2 ml-1">
                                <input type="hidden" name="{{ $dashboard['name'] }}" value="0">
                                <span class="">
                                    <label class="relative inline-flex h-6 w-[46px] items-center rounded-full transition-all duration-150 cursor-pointer">
                                        <input type="checkbox" name="{{ $dashboard['name'] }}" value="1" @if(oldSetting($dashboard['name'],$section)) checked @endif class="sr-only peer">
                                        <span class="w-11 h-6 bg-gray-200 peer-focus:outline-none ring-0 rounded-full peer dark:bg-gray-900 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-black-500"></span>
                                    </label>
                                </span> 
                                <small><label class="ml-2" style="display: inline; position: relative; top: -7px" >On Dashboard Page</label></small>
                            </div>
                            <div class="pt-2 ml-1">
                                <input type="hidden" name="{{ $auth['name'] }}" value="0">
                                <span class="">
                                    <label class="relative inline-flex h-6 w-[46px] items-center rounded-full transition-all duration-150 cursor-pointer">
                                        <input type="checkbox" name="{{ $auth['name'] }}" value="1" @if(oldSetting($auth['name'],$section)) checked @endif class="sr-only peer">
                                        <span class="w-11 h-6 bg-gray-200 peer-focus:outline-none ring-0 rounded-full peer dark:bg-gray-900 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-black-500"></span>
                                    </label>
                                </span> 
                                <small><label class="ml-2" style="display: inline; position: relative; top: -7px" >On Auth Page</label></small>
                            </div>

                        </div>
                    @endif

                @endforeach
            </div>
            @include('backend.setting.site_setting.include.form.__close_action')
        </div>
    </div>
@endsection
