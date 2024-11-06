

<div
    class="tab-pane space-y-5 fade show active"
    id="pills-informations"
    role="tabpanel"
    aria-labelledby="pills-informations-tab"
>
    @can('customer-basic-manage')
    <div class="card">
        <div class="card-body p-5">
            <form action="{{route('admin.user.update',$user->id)}}" method="post">
                @method('PUT')
                @csrf
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-5">
                    <div class="input-area relative">
                        <label for="" class="form-label">{{ __('First Name:') }}</label>
                        <input type="text" class="form-control" value="{{$user->first_name}}"
                               name="first_name" required="">
                    </div>
                    <div class="input-area relative">
                        <label for="" class="form-label">{{ __('Last Name:') }}</label>
                        <input type="text" class="form-control" value="{{$user->last_name}}" required=""
                               name="last_name">
                    </div>
                    <div class="input-area relative">

                        <label for="" class="form-label">{{ __('Country:') }}</label>
                        <select class="select2 form-control w-full" name="country" placeholder="Countries" >
                            @foreach( getCountries() as $country)
                                <option value="{{$country['name']}}" @selected( null != $user->country && in_array($country['name'],[$user->country]))>{{$country['name']}}</option>
                            @endforeach
                        </select>
                    </div>



                    <div class="input-area relative">
                        <label for="" class="form-label">{{ __('Phone:') }}</label>
                        <input type="text" name="phone" class="form-control" value="{{ safe($user->phone) }}" >
                    </div>
                    <div class="input-area relative">
                        <label for="" class="form-label">{{ __('Username:') }}</label>
                        <input type="text" class="form-control" name="username" value="{{ safe($user->username) }}"
                               required="">
                    </div>
                    <div class="input-area relative">
                        <label for="" class="form-label">{{ __('Email:') }}</label>
                        <input type="email" name="email" class="form-control" value="{{ safe($user->email) }}" >
                    </div>
                    <div class="input-area relative">
                        <label for="" class="form-label">{{ __('Gender:') }}</label>
                        <select name="gender" id="kycTypeSelect" class="select2 form-control w-full" required>
                            @foreach(['male','female','other'] as $gender)
                                <option @if($user->gender == $gender) selected @endif value="{{$gender}}">{{$gender}}</option>
                            @endforeach
                        </select>
                    </div>
{{--                    <div class="input-area">--}}
{{--                        <label for="" class="form-label">{{ __('Gender:') }}</label>--}}
{{--                        <input type="text" class="form-control" value="{{$user->gender}}" required=""--}}
{{--                               >--}}
{{--                    </div>--}}
{{--                    <div class="input-area">--}}
{{--                        <label for="" class="form-label">{{ __('Date of Birth:') }}</label>--}}
{{--                        <input type="text"  name="date_of_birth" class="form-control" value="{{$user->date_of_birth}}" >--}}
{{--                    </div>--}}
                    <div class="input-area relative">
                        <label for="exampleFormControlInput1" class="form-label">{{ __('Date of Birth') }}</label>
                        <input type="date" name="date_of_birth" class="form-control " value="{{safe($user->date_of_birth) }}">
                    </div>
                    <div class="input-area relative">
                        <label for="" class="form-label">{{ __('City:') }}</label>
                        <input type="text" name="city" class="form-control" value="{{safe($user->city)}}">
                    </div>
                    <div class="input-area relative">
                        <label for="" class="form-label">{{ __('Zip Code:') }}</label>
                        <input type="text" class="form-control" name="zip_code" value="{{$user->zip_code}}">
                    </div>
                    <div class="input-area relative">
                        <label for="" class="form-label">{{ __('Address:') }}</label>
                        <input type="text" class="form-control" name="address" value="{{$user->address}}">
                    </div>
                    <div class="input-area relative">
                        <label for="" class="form-label">{{ __('Joining Date:') }}</label>
                        <input type="text" class="form-control"
                               value="{{ carbonInstance($user->created_at)->toDayDateTimeString() }}"
                               required="" disabled>
                    </div>
                    @if($user->notes)
                        <div class="input-area relative lg:col-span-3">
                            <label for="" class="form-label">{{ __('Notes:') }}</label>
                            <textarea type="text"  name="notes" class="form-control"
                            > {{ $user->notes }}</textarea>
                        </div>
                    @endif
                    <div class="input-area relative">
                        <label for="" class="form-label">{{ __('Assign Group:') }}</label>
                        <select name="group_id" class="form-control">
                            <option value="">Select</option>
                            @foreach($customerGroups as $group)
                                <option value="{{ $group->id }}" {{ $user->customerGroups->pluck('id')->contains($group->id) ? 'selected' : '' }}>
                                    {{ $group->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="input-area relative">
                        <label for="risk_profile_tags" class="form-label">{{ __('Risk Profile Tags:') }}</label>
                        <select name="risk_profile_tags[]" class="select2 form-control w-full" multiple="multiple" data-placeholder="Select Tags" id="riskProfileTagsSelect">
                            @foreach($riskProfileTags as $tag)
                                <option value="{{ $tag->id }}"
                                    @if($user->riskProfileTags->pluck('id')->contains($tag->id))
                                        selected
                                    @endif>
                                    {{ $tag->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="input-area relative">
                        <label for="" class="form-label">{{ __('Lead Campaign:') }}</label>
                        <input type="text" class="form-control" name="lead_campaign" value="">
                    </div>

                    <div class="input-area relative">
                        <label for="" class="form-label">{{ __('Lead Source:') }}</label>
                        <input type="text" class="form-control" name="lead_source" value="">
                    </div>

                    <div class="input-area relative lg:col-span-3">
                        <label for="" class="form-label">{{ __('Comment:') }}</label>
                        <textarea type="text"  name="comment" class="form-control" rows="5"> {{ $user->comment }}</textarea>
                    </div>
                    <div class="input-area relative text-right lg:col-span-3">
                        <button type="submit" class="btn btn-dark inline-flex items-center justify-center">
                            {{ __('Save Changes') }}
                        </button>
                    </div>

                </div>
            </form>
        </div>
    </div>
    @endcan

</div>
