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
    <div class="col-xl-12 col-lg-12 col-md-12 col-12">
        <div class="site-card">
            <div class="site-card-header">
                <h3 class="title">{{ __($fields['title']) }}</h3>
            </div>
            <div class="site-card-body">
                @include('backend.setting.site_setting.include.form.__open_action')
                <div class="site-input-groups row gy-3">
                    @foreach($fields['elements']  as $key => $field)
                        @if($field['type'] == 'checkbox')
                            <div class="col-sm-3">
                                <div class="form-switch ps-0">
                                    <input class="form-check-input" type="hidden" value="0" name="{{$field['name']}}"/>
                                    <div class="switch-field same-type m-0">
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
                            </div>
                        @else
                            <div class="col-sm-3 col-label pt-0">{{ __($field['label']) }}</div>
                            <div class="col-sm-6">
                                <input type="{{$field['type']}}" name="{{ $field['name'] }}" class="box-input @if($errors->has($field['name'])) has-error @endif" placeholder="URL" value="{{oldSetting($field['name'],$section)}}">
                            </div>
                        @endif
                    @endforeach
                </div>
                @include('backend.setting.site_setting.include.form.__close_action')
            </div>
        </div>
    </div>
@endsection
