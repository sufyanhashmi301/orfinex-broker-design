@extends('backend.layouts.app')
@section('title')
    {{ __('Edit User') }}
@endsection
@section('style')
    <style>
        @media (min-width: 1023px) {
            .tab-pane .card {
                height: 567px !important
            }
        }
    </style>
@endsection
@section('content')
    <div class="space-y-5 profile-page">
        <div class="grid grid-cols-12 gap-6">

            <div class="2xl:col-span-3 lg:col-span-4 col-span-12">
                @can('all-type-status')
                    @include('backend.user.includes.__settings_section')
                @endcan
            </div>

            <div class="2xl:col-span-9 lg:col-span-8 col-span-12">
                @include('backend.user.includes.__info_cards_section')
                @include('backend.user.includes.__tabs_section')
            </div>
        </div>
    </div>

    <!-- Modals -->
    @can('customer-mail-send')
        @include('backend.user.modals.__mail_send', [
            'name' => $user->first_name . ' ' . $user->last_name,
            'id' => $user->id,
        ])
    @endcan
    @include('backend.user.modals.__add_account_modal')
    @include('backend.user.modals.__delete_user', ['id' => $user->id])


@endsection
@section('script')
    
@endsection
