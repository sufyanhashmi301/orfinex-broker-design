@extends('backend.setting.organization.index')
@section('title')
    {{ __('Social Logins') }}
@endsection
@section('organization-content')
    <div class="pageTitle flex justify-between flex-wrap items-center mb-6">
        <h4 class="font-medium text-xl capitalize text-slate-500 dark:text-slate-400 inline-block ltr:pr-4 rtl:pl-4 mb-1 sm:mb-0">
            @yield('title')
        </h4>
    </div>
    <div class="grid xl:grid-cols-3 md:grid-cols-2 grid-cols-1 gap-5">
        @foreach($socialLogins as $socialLogin)
            <div class="card border hover:shadow-lg">
                <div class="card-header items-center noborder !p-4">
                    @switch($socialLogin->driver)
                        @case('facebook')
                            <img class="inline-block h-10" src="https://cdn.brokeret.com/crm-assets/admin/social/fb-login.webp" alt=""/>
                        @break
                        @case('twitter')
                            <img class="inline-block h-10" src="https://cdn.brokeret.com/crm-assets/admin/social/x-login.webp" alt=""/>
                        @break
                        @case('instagram')
                            <img class="inline-block h-10" src="https://cdn.brokeret.com/crm-assets/admin/social/instagram-login.webp" alt=""/>
                        @break
                        @case('linkedin')
                            <img class="inline-block h-10" src="https://cdn.brokeret.com/crm-assets/admin/social/linkedin-login.webp" alt=""/>
                        @break
                        @case('google')
                            <img class="inline-block h-10" src="https://cdn.brokeret.com/crm-assets/admin/social/google-login.webp" alt=""/>
                        @break
                        @case('discord')
                            <img class="inline-block h-10" src="https://cdn.brokeret.com/crm-assets/admin/social/discord-login.webp" alt=""/>
                        @break
                        @default
                            <img class="inline-block h-10" src="https://cdn.brokeret.com/crm-assets/admin/social/discord-login.webp" alt=""/>
                    @endswitch
                    <button type="button" class="action-btn cursor-pointer editBtn dark:text-slate-300" data-id="{{ $socialLogin->id }}">
                        <iconify-icon icon="lucide:settings-2"></iconify-icon>
                    </button>
                </div>
                <div class="card-body p-4 pt-2">
                    <div class="flex items-center justify-between mb-3">
                        <h4 class="text-base font-medium dark:text-white mr-1">{{ $socialLogin->title }}</h4>
                        @if($socialLogin->status)
                            <div class="badge badge-success capitalize">
                                {{ __('Activated') }}
                            </div>
                        @else
                            <div class="badge badge-danger capitalize">
                                {{ __('DeActivated') }}
                            </div>
                        @endif
                    </div>
                    <p class="text-sm dark:text-slate-300">{{ $socialLogin->description }}</p>
                </div>
            </div>
        @endforeach
    </div>

    {{--Modal for update social login--}}
    @can('social-login-edit')
        @include('backend.setting.organization.social_login.__edit_modal')
    @endcan

@endsection
@section('organization-script')
    <script !src="">
        $('body').on('click', '.editBtn', function (event){
            "use strict";
            event.preventDefault();
            $('#edit-social-login-body').empty();
            var recordId = $(this).data('id');
            var url = "{{ route('admin.social.edit', ':id') }}".replace(':id', recordId);

            $.get(url, function (response) {
                $('#editSocialLoginModal').modal('show');
                $('#edit-social-login-body').append(response);
            });
        });
    </script>
@endsection
