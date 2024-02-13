<div class="card mb-5">
    <header class=" card-header">
        <h4 class="card-title">
            {{ __('Profile Settings') }}
        </h4>
    </header>
    <div class="card-body p-6">
        <form action="{{ route('user.setting.profile-update') }}" id="profile-update-form" method="post" enctype="multipart/form-data">
            @csrf
            <div class="progress-steps-form">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div class="input-area relative">
                        <label for="exampleFormControlInput1" class="form-label">{{ __('First Name') }}</label>
                        <input type="text" class="form-control !text-lg" name="first_name" value="{{ $user->first_name }}" placeholder="First Name" />
                    </div>
                    <div class="input-area relative">
                        <label for="exampleFormControlInput1" class="form-label">{{ __('Last Name') }}</label>
                        <input type="text" class="form-control !text-lg" name="last_name" value="{{ $user->last_name }}" placeholder="Last Name" />
                    </div>
                    <div class="input-area relative">
                        <label for="exampleFormControlInput1" class="form-label">{{ __('Username') }}</label>
                        <input type="text" class="form-control !text-lg" name="username" value="{{ $user->username }}" placeholder="Username" />
                    </div>
                    <div class="input-area relative">
                        <label for="exampleFormControlInput1" class="form-label">{{ __('Gender') }}</label>
                        <div class="input-group select2-lg">
                            <select name="gender" id="kycTypeSelect" class="select2 form-control !text-lg w-full mt-2 py-2" required>
                                @foreach(['male','female','other'] as $gender)
                                    <option @if($user->gender == $gender) selected @endif value="{{$gender}}">{{$gender}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="input-area relative">
                        <label for="exampleFormControlInput1" class="form-label">{{ __('Date of Birth') }}</label>
                        <input type="date" name="date_of_birth" class="form-control !text-lg" value="{{ $user->date_of_birth }}" placeholder="Date of Birth"/>
                    </div>

                    <div class="input-area relative">
                        <label for="exampleFormControlInput1" class="form-label">{{ __('Email Address') }}</label>
                        <input type="email" disabled class="form-control !text-lg disabled" value="{{ $user->email }}" placeholder="Email Address" />
                    </div>
                    <div class="input-area relative phone-input-wrapper">
                        <label for="exampleFormControlInput1" class="form-label">{{ __('Phone') }}</label>
                        <input type="text" class="form-control !text-lg w-full" name="phone" id="phone" value="{{ $user->phone }}" placeholder="Phone"/>
                    </div>
                    <div class="input-area relative">
                        <label for="exampleFormControlInput1" class="form-label">
                            {{ __('Country') }}
                        </label>
                        <input type="text" class="form-control !text-lg disabled" value="{{ $user->country }}" placeholder="Country" disabled />
                    </div>

                    <div class="input-area relative">
                        <label for="exampleFormControlInput1" class="form-label">{{ __('City') }}</label>
                        <input type="text" class="form-control !text-lg" name="city" value="{{ $user->city }}" placeholder="City" />
                    </div>
                    <div class="input-area relative">
                        <label for="exampleFormControlInput1" class="form-label">{{ __('Zip') }}</label>
                        <input type="text" class="form-control !text-lg" name="zip_code" value="{{ $user->zip_code }}" placeholder="Zip" />
                    </div>
                    <div class="input-area relative">
                        <label for="exampleFormControlInput1" class="form-label">{{ __('Address') }}</label>
                        <input type="text" class="form-control !text-lg" name="address" value="{{ $user->address }}" placeholder="Address" />
                    </div>
                    <div class="input-area relative">
                        <label for="exampleFormControlInput1" class="form-label">{{ __('Joining Date') }}</label>
                        <input type="text" class="form-control !text-lg disabled" value="{{ carbonInstance($user->created_at)->toDayDateTimeString() }}" placeholder="Joining Date" disabled />
                    </div>

                    <div class="mt-4">
                        <button type="submit" class="btn btn-dark" id="profile-update-save">{{ __('Save Changes') }}</button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
