<div
    class="tab-pane space-y-5 fade show active"
    id="pills-informations"
    role="tabpanel"
    aria-labelledby="pills-informations-tab"
>
    @can('customer-basic-manage')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">{{ __('Basic Info') }}</h3>
        </div>
        <div class="card-body p-5">
            <form action="{{route('admin.user.update',$user->id)}}" method="post">
                @method('PUT')
                @csrf
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-5">
                    <div class="input-area">
                        <label for="" class="form-label">{{ __('First Name:') }}</label>
                        <input type="text" class="form-control" value="{{$user->first_name}}"
                               name="first_name" required="">
                    </div>
                    <div class="input-area">
                        <label for="" class="form-label">{{ __('Last Name:') }}</label>
                        <input type="text" class="form-control" value="{{$user->last_name}}" required=""
                               name="last_name">
                    </div>
                    <div class="input-area">
                        <label for="" class="form-label">{{ __('Country:') }}</label>
                        <input type="text" class="form-control" value="{{$user->country}}" disabled>
                    </div>
                    <div class="input-area">
                        <label for="" class="form-label">{{ __('Phone:') }}</label>
                        <input type="text" class="form-control" value="{{ safe($user->phone) }}" disabled>
                    </div>
                    <div class="input-area">
                        <label for="" class="form-label">{{ __('Username:') }}</label>
                        <input type="text" class="form-control" name="username" value="{{ safe($user->username) }}"
                               required="">
                    </div>
                    <div class="input-area">
                        <label for="" class="form-label">{{ __('Email:') }}</label>
                        <input type="email" class="form-control" value="{{ safe($user->email) }}" disabled>
                    </div>
                    <div class="input-area">
                        <label for="" class="form-label">{{ __('Gender:') }}</label>
                        <input type="text" class="form-control" value="{{$user->gender}}" required=""
                               disabled>
                    </div>
                    <div class="input-area">
                        <label for="" class="form-label">{{ __('Date of Birth:') }}</label>
                        <input type="text" class="form-control" value="{{$user->date_of_birth}}" disabled>
                    </div>
                    <div class="input-area">
                        <label for="" class="form-label">{{ __('City:') }}</label>
                        <input type="text" name="city" class="form-control" value="{{$user->city}}">
                    </div>
                    <div class="input-area">
                        <label for="" class="form-label">{{ __('Zip Code:') }}</label>
                        <input type="text" class="form-control" name="zip_code" value="{{$user->zip_code}}">
                    </div>
                    <div class="input-area">
                        <label for="" class="form-label">{{ __('Address:') }}</label>
                        <input type="text" class="form-control" name="address" value="{{$user->address}}">
                    </div>
                    <div class="input-area">
                        <label for="" class="form-label">{{ __('Joining Date:') }}</label>
                        <input type="text" class="form-control"
                               value="{{ carbonInstance($user->created_at)->toDayDateTimeString() }}"
                               required="" disabled>
                    </div>

                    <div class="input-area text-right lg:col-span-3">
                        <button type="submit" class="btn btn-dark inline-flex items-center justify-center">
                            {{ __('Save Changes') }}
                        </button>
                    </div>

                </div>
            </form>
        </div>
    </div>
    @endcan
    @can('customer-change-password')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">{{ __('Change Password') }}</h3>
        </div>
        <div class="card-body p-5">
            <form action="{{route('admin.user.password-update',$user->id)}}" method="post">
                @csrf
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-5">
                    <div class="input-area">
                        <label for="" class="form-label">{{ __('New Password:') }}</label>
                        <input type="password" name="new_password" class="form-control" required="">
                    </div>
                    <div class="input-area">
                        <label for="" class="form-label">{{ __('Confirm Password:') }}</label>
                        <input type="password" name="new_confirm_password" class="form-control"
                               required="">
                    </div>
                    <div class="input-area text-right lg:col-span-2">
                        <button type="submit" class="btn btn-dark inline-flex items-center justify-center">
                            {{ __('Change Password') }}
                        </button>
                    </div>
                </div>
            </form>

        </div>
    </div>
    @endcan

</div>
