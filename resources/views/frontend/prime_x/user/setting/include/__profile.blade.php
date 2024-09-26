<div class="card mb-10">
    <div class="card-body p-6">
        <div class="flex items-center justify-between flex-wrap gap-5">
            <div class="flex items-center">
                <div class="flex-none">
                    <div class="h-[100px] w-[100px] rounded-full border-[2.5px] border-primary ltr:mr-3 rtl:ml-3">
                        <img src="@if(auth()->user()->avatar && file_exists('assets/'.auth()->user()->avatar)) {{asset($user->avatar)}} @else {{ asset('frontend/images/all-img/user.png') }}@endif" alt="" class="block w-full h-full object-cover rounded-full">
                    </div>
                </div>
                <div class="flex-1 text-start">
                    <div class="text-left">
                        <span class="text-xl text-slate-900 dark:text-white">{{auth()->user()->full_name}}</span><br>
                        <span class="flex items-center text-slate-400 text-sm font-normal">
                            {{ $user->rank->ranking }}
                            <iconify-icon class="text-base ml-1" icon="bxs:badge-check" style="color: #FED000;"></iconify-icon>
                        </span>
                    </div>
                </div>
            </div>
            <div class="flex flex-wrap sm:justify-end justify-center items-center gap-3">
                <div class="text-center bg-slate-50 dark:bg-body rounded py-3 px-6">
                    <p class="text-xl text-slate-900 dark:text-white mb-2">
                        ${{auth()->user()->totalForexBalance()}}
                    </p>
                    <p class="text-base text-slate-600">
                        {{ __('Balance') }}
                    </p>
                </div>
                <div class="text-center bg-slate-50 dark:bg-body rounded py-3 px-6">
                    <p class="text-xl text-slate-900 dark:text-white mb-2">
                        ${{auth()->user()->totalForexEquity()}}
                    </p>
                    <p class="text-base text-slate-600">
                        {{ __('Equity') }}
                    </p>
                </div>
                <div class="text-center bg-slate-50 dark:bg-body rounded py-3 px-6">
                    <p class="text-xl text-slate-900 dark:text-white mb-2">
                        0
                    </p>
                    <p class="text-base text-slate-600">
                        {{ __('Rewards') }}
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

<h4 class="text-xl text-slate-900 mb-3">
    {{ __('Personal Information') }}
</h4>
<div class="card">
    <div class="card-body p-6">
        <form action="{{ route('user.setting.profile-update') }}" id="profile-update-form" method="post" enctype="multipart/form-data">
            @csrf
            <div class="progress-steps-form">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
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
                </div>

                <div class="text-right mt-4">
                    <button type="submit" class="btn btn-primary inline-flex items-center justify-center" id="profile-update-save">{{ __('Save Changes') }}</button>
                </div>
            </div>
        </form>
    </div>
</div>
