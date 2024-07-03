@extends('backend.links.index')
@section('links-title')
    {{ __('Document Links') }}
@endsection
@section('title')
    {{ __('Document Links') }}
@endsection
@section('links-content')
    <?php
    $section = 'document_links';

    $fields = config('setting.document_links');
    //   dd($fields);
    ?>
    <div class="card">
        <div class="card-body p-6">
            @include('backend.setting.site_setting.include.form.__open_action')
            <div class="grid grid-cols-1 md:grid-cols-2 items-end gap-5">
                @foreach($fields['elements']  as $key => $field)
                <div class="input-area relative">
                    @if($field['type'] == 'checkbox')
                    <div class="">
                        <input class="form-check-input" type="hidden" value="0" name="{{$field['name']}}"/>
                        <div class="switch-field flex overflow-hidden same-type m-0">
                            <input
                                type="radio"
                                id="{{$field['name'], $key}}"
                                name="{{$field['name']}}"
                                value="1"
                                @if(oldSetting($field['name'],$section)) checked @endif
                            />
                            <label for="{{$field['name'], $key}}">{{ __('Enable') }}</label>
                            <input
                                type="radio"
                                id="disable-{{$field['name'], $key}}"
                                name="{{$field['name']}}"
                                value="0"
                                @if(!oldSetting($field['name'],$section)) checked @endif
                            />
                            <label for="disable-{{$field['name'], $key}}">{{ __('Disabled') }}</label>
                        </div>
                    </div>
                    @else
                        <label for="" class="form-label">{{ __($field['label']) }}</label>
                        <input type="{{$field['type']}}" name="{{ $field['name'] }}" class="form-control  @if($errors->has($field['name'])) has-error @endif" placeholder="URL" value="{{oldSetting($field['name'],$section)}}">
                    @endif
                </div>
                @endforeach
            </div>
            @include('backend.setting.site_setting.include.form.__close_action')
        </div>
    </div>
@endsection
