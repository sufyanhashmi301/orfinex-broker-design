@extends('backend.server_settings.index')
@section('server-setting-title')
    {{ __('Server Setting') }}
@endsection
@section('title')
    {{ __('Server Setting') }}
@endsection
@section('server-setting-content')
    <div class="col-xl-6 col-12">
        <div class="site-card">
            <div class="site-card-header">
                <h3 class="title">{{ __('Main Server') }}</h3>
            </div>
            <div class="site-card-body">
                <form action="">
                    <div class="site-input-groups row align-items-center">
                        <div class="col-sm-4 col-label pt-0">Server Name</div>
                        <div class="col-sm-8">
                            <div class="input-group joint-input">
                                <input class="form-control" type="text" name="server_name" placeholder="BrokerServer-MT5">
                            </div>
                        </div>
                    </div>
                    <div class="site-input-groups row align-items-center">
                        <div class="col-sm-4 col-label pt-0">Server API EndPoint</div>
                        <div class="col-sm-8">
                            <div class="input-group joint-input">
                                <input class="form-control" type="text" name="server_name" placeholder="https://8.217.97.85:7762/api">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="offset-sm-4 col-sm-8">
                            <button type="submit" class="site-btn-sm primary-btn w-100">
                                Save Changes
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="col-xl-6 col-12">
        <div class="site-card">
            <div class="site-card-header">
                <h3 class="title">{{ __('Additional Servers') }}</h3>
            </div>
            <div class="site-card-body">
                <form action="">
                    <div class="site-input-groups row align-items-center">
                        <div class="col-sm-4 col-label pt-0">Real Server</div>
                        <div class="col-sm-8">
                            <div class="input-group joint-input">
                                <input class="form-control" type="text" name="server_name">
                            </div>
                        </div>
                    </div>
                    <div class="site-input-groups row align-items-center">
                        <div class="col-sm-4 col-label pt-0">Demo Server</div>
                        <div class="col-sm-8">
                            <div class="input-group joint-input">
                                <input class="form-control" type="text" name="server_name">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="offset-sm-4 col-sm-8">
                            <button type="submit" class="site-btn-sm primary-btn w-100">
                                Save Changes
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection