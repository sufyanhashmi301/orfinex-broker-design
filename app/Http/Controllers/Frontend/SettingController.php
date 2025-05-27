<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Traits\ImageUpload;
use Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use PragmaRX\Google2FALaravel\Support\Authenticator;
use App\Models\User;
use App\Models\UserLanguage;

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
//        dd($input);
        $user = \Auth::user();
        $validator = Validator::make($request->all(), [
            'first_name' => 'required',
            'last_name' => 'required',
            'username' => 'required|unique:users,username,'.$user->id,
            'gender' => 'required',
            'date_of_birth' => 'date',
            'phone' => 'required',
        ]);

        if ($validator->fails()) {
            notify()->error($validator->errors()->first(), __('Error'));

            return redirect()->back();
        }

        $data = [
            'avatar' => $request->hasFile('avatar') ? self::imageUploadTrait($input['avatar'], $user->avatar) : $user->avatar,
            'first_name' => $input['first_name'],
            'last_name' => $input['last_name'],
            'username' => $input['username'],
            'gender' => $input['gender'],
            'date_of_birth' => $input['date_of_birth'] == '' ? null : $input['date_of_birth'],
            'phone' => $input['phone'],
            'city' => $input['city'],
            'zip_code' => $input['zip_code'],
            'address' => $input['address'],
        ];

        $user->update($data);

        notify()->success(__('Your Profile Updated successfully'));

        return redirect()->back();
    }

    public function updateAvatar(Request $request)
    {
        $user = \Auth::user();
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
        $user = \Auth::user();
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
        $user = \Auth::user();
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
        $user = \Auth::user();

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
        $user = \Auth::user();
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

        $user = \Auth::user();
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

        $user = \Auth::user();
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
