@extends('backend.layouts.app')
@section('title')
    {{ __('Manage Staff') }}
@endsection
@section('content')
    <?php
    $user = \Auth::user();
    ?>
    <div class="main-content">


        <div class="page-title">
            <div class="container-fluid">
                <div class="row justify-content-center">
                    <div class="col-xl-8">
                        <div class="title-content">
                            <h2 class="title">{{ __('Manage 2FA Security') }}</h2>
                            <a href="{{ url()->previous() }}" class="title-btn"><i
                                    icon-name="corner-down-left"></i>{{ __('Back') }}</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="container-fluid">

            <div class="row">

                <div class="col-xl-12">
                    <div class="site-card">
                        <div class="site-card-body">

                            @include('backend.staff.include.__two_fa')
                        </div>
                    </div>
                </div>
            </div>
        </div>



    </div>
@endsection

@section('script')
    <script>


    </script>
@endsection
