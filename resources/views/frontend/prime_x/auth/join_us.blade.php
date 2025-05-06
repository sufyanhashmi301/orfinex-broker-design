@php
    $fields = config('setting.social_links');

    $has_icons = false;

    foreach ($fields['elements'] as $key => $field) {
        if (str_contains($field['name'], '_auth')) {
            if (setting($field['name']) == 1) {
                $has_icons = true;
                break;
            }
        }
    }
@endphp
@if ($has_icons)

    <div class="relative border-b-[#9AA2AF] border-opacity-[16%] border-b pt-6">
        <div class="absolute inline-block bg-white dark:bg-slate-800 left-1/2 top-1/2 transform -translate-x-1/2 px-4 min-w-max text-sm text-slate-500 dark:text-slate-400font-normal">
            {{ __('Connect With Us') }}
        </div>
    </div>

    <div style="padding-top: 20px; left: 50%; position: relative; transform: translateX(-50%); display: inline-block;">
        <style>
            .nav-icon {
                border-radius: 50% !important;
                display: inline-block;
                margin-right: 20px
            }
            .nav-icon svg {
                height: 28px !important;
                width: 28px !important;
                
            }
        </style>
        @foreach ($fields['elements'] as $key => $field)
            @php
                $is_enabled = false;
                $settings_name = '';
                if(str_contains($field['name'], '_auth') ) {
                    if(setting($field['name']) == 1) {
                        $is_enabled = true;
                        $settings_name = str_replace('_on_auth', '', $field['name']);
                        $label = str_replace( '_', ' ', str_replace( 'social_', '', $settings_name) );
                    }
                }
            @endphp
            @if ($is_enabled)
                @php $has_icons = true @endphp
                <div class="nav-icon">
                    <a href="{{ setting($fields['elements'][$loop->index - 3]['name'], 'social_links')  }}" target="__blank">{!! $fields['elements'][$loop->index - 3]['icon'] !!}</a>
                </div>
            @endif
        @endforeach

    </div>
@endif