<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Traits\ImageUpload;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use PragmaRX\Google2FALaravel\Support\Authenticator;
use App\Models\User;
use App\Models\UserLanguage;
use libphonenumber\PhoneNumberUtil;
use libphonenumber\PhoneNumberFormat;
use libphonenumber\NumberParseException;

class SettingController extends Controller
{
    use ImageUpload;

    public function settings()
    {
        return view('frontend::user.setting.index');
    }

    public function profile()
    {
        return view('frontend::user.setting.profile.index');
    }

    public function security()
    {
        return view('frontend::user.setting.security.index');
    }

    public function profileUpdate(Request $request)
    {
        $input = $request->all();
        $user = Auth::user();
        
        // Get setting permissions for each field
        $nameEditEnabled = (bool) setting('customer_name_edit', 'customer_permission');
        $phoneEditEnabled = (bool) setting('customer_phone_edit', 'customer_permission');
        $usernameEditEnabled = (bool) setting('customer_username_edit', 'customer_permission');
        $emailEditEnabled = (bool) setting('customer_email_edit', 'customer_permission');
        $countryEditEnabled = (bool) setting('customer_country_edit', 'customer_permission');
        $dobEditEnabled = (bool) setting('customer_dob_edit', 'customer_permission');
        $isPhoneRestricted = (bool) setting('phone_number_restriction', 'permission');
        
        // Build conditional validation rules
        $validationRules = [];
        
        // Only validate fields that are enabled for editing and have existing values or are being updated
        if ($nameEditEnabled || !$user->first_name) {
            $validationRules['first_name'] = 'required|string|max:255';
        }
        
        if ($nameEditEnabled || !$user->last_name) {
            $validationRules['last_name'] = 'required|string|max:255';
        }
        
        if ($usernameEditEnabled || !$user->username) {
            $validationRules['username'] = 'required|string|max:255|unique:users,username,'.$user->id;
        }
        
        if ($emailEditEnabled && $request->has('email')) {
            $validationRules['email'] = 'required|email|max:255|unique:users,email,'.$user->id;
        }
        
        if ($phoneEditEnabled || !$user->phone) {
            $phoneRules = ['required', 'string', 'max:255'];
            if ($isPhoneRestricted) {
                $phoneRules[] = 'unique:users,phone,' . $user->id;
            }
            $validationRules['phone'] = $phoneRules;
        }
        
        if ($countryEditEnabled && $request->has('country')) {
            $validationRules['country'] = 'required|string|max:255';
        }
        
        if ($dobEditEnabled || !$user->date_of_birth) {
            $validationRules['date_of_birth'] = 'nullable|date';
        }
        
        // Always validate gender and optional fields
        $validationRules['gender'] = 'required|in:male,female,other';
        $validationRules['city'] = 'nullable|string|max:255';
        $validationRules['zip_code'] = 'nullable|string|max:10';
        $validationRules['address'] = 'nullable|string|max:255';

        $validator = Validator::make($request->all(), $validationRules);


        // Cross-validate phone using helper and normalize to E.164 against selected country
        $validator->after(function ($v) use ($request, $user) {
            $rawPhone = $request->input('formatted_phone') ?: $request->input('phone');
            $countryRaw = (string) $request->input('country', $user->country);
            $validation = validateAndNormalizePhone($rawPhone, $countryRaw, $user->country);
            if (!$validation['valid']) {
                $v->errors()->add('phone', $validation['error'] ?? 'Invalid phone number.');
                return;
            }
            $request->merge(['__normalized_phone' => $validation['e164']]);
        });

        if ($validator->fails()) {
            notify()->error($validator->errors()->first(), __('Error'));
            return redirect()->back()->withInput();
        }

        // Build data array with only enabled fields or fields that don't have existing values
        $data = [];
        
        // Determine normalized phone value post-validation
        $normalizedPhone = $request->input('__normalized_phone');
        $rawPhone = $request->input('phone');
        $phoneToStore = $normalizedPhone ?? ($rawPhone ?? $user->phone);
        
        $countryRawForStore = (string) $request->input('country', $user->country);
        $countryNameForStore = (strpos($countryRawForStore, ':') !== false) ? explode(':', $countryRawForStore)[0] : $countryRawForStore;
        
        // If no country provided but phone detected a country, use it when allowed
        if (empty($request->input('country')) && !empty($phoneToStore)) {
            $detected = getCountryFromPhone($phoneToStore);
            if ($detected && isset($detected['name'])) {
                $countryNameForStore = $detected['name'];
            }
        }
        
        // Handle avatar upload
        if ($request->hasFile('avatar')) {
            $data['avatar'] = self::imageUploadTrait($input['avatar'], $user->avatar);
        }
        
        // Only update fields that are enabled for editing or don't have existing values
        if (($nameEditEnabled || !$user->first_name) && $request->has('first_name')) {
            $data['first_name'] = $input['first_name'];
        }
        
        if (($nameEditEnabled || !$user->last_name) && $request->has('last_name')) {
            $data['last_name'] = $input['last_name'];
        }
        
        if (($usernameEditEnabled || !$user->username) && $request->has('username')) {
            $data['username'] = $input['username'];
        }
        
        if ($emailEditEnabled && $request->has('email')) {
            $data['email'] = $input['email'];
        }
        
        if ($phoneEditEnabled || !$user->phone) {
            // Save E.164 normalized phone
            $data['phone'] = $phoneToStore;
        }
        
        if ($countryEditEnabled && $request->has('country')) {
            $data['country'] = $input['country'];
        }
        
        if (($dobEditEnabled || !$user->date_of_birth) && $request->has('date_of_birth')) {
            $data['date_of_birth'] = $input['date_of_birth'] == '' ? null : $input['date_of_birth'];
        }
        
        // Always allow updating these optional fields
        if ($request->has('gender')) {
            $data['gender'] = $input['gender'];
        }
        if ($request->has('city')) {
            $data['city'] = $input['city'];
        }
        if ($request->has('zip_code')) {
            $data['zip_code'] = $input['zip_code'];
        }
        if ($request->has('address')) {
            $data['address'] = $input['address'];
        }

        // Only update if we have data to update
        if (!empty($data)) {
            $user->update($data);
        }


        notify()->success(__('Your Profile Updated successfully'));
        return redirect()->back();
    }

    public function updateAvatar(Request $request)
    {
        $user = Auth::user();
        $domain = Str::slug(request()->getHttpHost());

        if ($request->hasFile('avatar')) {
            $file = $request->file('avatar');

            // Safe filename: timestamp + slug of original name
            $filename = time() . '_' . Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME)) . '.' . $file->getClientOriginalExtension();

            // Path format: {domain}/staff/{user_id}/profile-photos/
            $directory = "{$domain}/user/{$user->id}/profile-photos";
            $path = "{$directory}/{$filename}";

            // Upload to R2
            Storage::disk('r2')->putFileAs($directory, $file, $filename, 'public');
            $assetUrl = config('filesystems.disks.r2.url');
            $avatarPath = rtrim($assetUrl, '/') . '/' . $path;
        } else {
            $avatarPath = $user->avatar;
        }

        auth()->user()->update(['avatar' => $avatarPath]);

        return response()->json(['success' => true]);
    }

    public function infoUpdate(Request $request)
    {
        $input = $request->all();
//        dd($input);
        $user = Auth::user();
        $validator = Validator::make($request->all(), [
            'email' => 'sometimes|unique:users,email,'.$user->id,
            'phone' => 'sometimes',
        ]);

        if ($validator->fails()) {
            notify()->error($validator->errors()->first(), __('Error'));

            return redirect()->back();
        }

        $data = [];
//        if(isset($input['email'])) {
//            $data['email'] = $input['email'];
//        }
        if(isset($input['phone'])) {
            $data['phone'] = $input['phone'];
        }
        if(isset($input['address'])) {
            $data['address'] = $input['address'];
        }

        $user->update($data);

        notify()->success(__('Your Profile Updated successfully'));

        return redirect()->route('user.setting.show');

    }

    public function twoFa()
    {
        $user = Auth::user();
        $google2fa = app('pragmarx.google2fa');
        $secret = $google2fa->generateSecretKey();
//dd($google2fa,$secret);
        $user->update([
            'google2fa_secret' => $secret,
        ]);
        notify()->success(__('QR Code And Secret Key Generate successfully'));

        return redirect()->back();

    }

    public function actionTwoFa(Request $request)
    {
        $user = Auth::user();

        if ($request->status == 'disable') {

            if (Hash::check(request('one_time_password'), $user->password)) {
                $user->update([
                    'two_fa' => 0,
                ]);
                notify()->success(__('2Fa Authentication Disable successfully'));

                return redirect()->back();
            }

            notify()->warning(__('Wrong Your Password'));

            return redirect()->back();

        } elseif ($request->status == 'enable') {
            session([
                config('google2fa.session_var') => [
                    'auth_passed' => false,
                ],
            ]);

            $authenticator = app(Authenticator::class)->boot($request);
            if ($authenticator->isAuthenticated()) {

                $user->update([
                    'two_fa' => 1,
                ]);
                notify()->success(__('2Fa Authentication Enable successfully'));

                return redirect()->back();

            }

            notify()->warning(__('2Fa Authentication Wrong One Time Key'));

            return redirect()->back();
        }
    }

    public function preference()
    {
        $user = Auth::user();
        $selectedLanguage = UserLanguage::where('user_id', $user->id)->value('language') ?? 'english';

        // Fetch the user's theme preference
        $activeTheme = User::where('id', $user->id)->value('user_theme') ?? 'light';

        return view('frontend::user.setting.communication.index', compact('selectedLanguage', 'activeTheme'));
    }

    public function updateLanguage(Request $request)
    {
        $request->validate([
            'language' => 'required',
        ]);

        $user = Auth::user();
        $userLanguage = UserLanguage::where('user_id', $user->id)->first();

        if ($userLanguage) {
            $userLanguage->language = $request->language;
            $userLanguage->save();
        } else {
            UserLanguage::create([
                'user_id' => $user->id,
                'language' => $request->language,
            ]);
        }

        notify()->success(__('Language Selected successfully'));
        return redirect()->back();
    }

    public function updateUserTheme(Request $request)
    {
        $request->validate([
            'user_theme' => 'required|in:light,dark',
        ]);

        $user = Auth::user();
        $user->update([
            'user_theme' => $request['user_theme'],
        ]);

        notify()->success(__('Your theme has been updated successfully.'));
        return redirect()->back();
    }

    public function tools()
    {
        return view('frontend::user.setting.tools');
    }
}
