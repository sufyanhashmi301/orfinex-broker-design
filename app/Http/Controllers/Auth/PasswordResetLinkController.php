<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Page;
use App\Models\User;
use App\Traits\NotifyTrait;
use Carbon\Carbon;
use DB;
use Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Str;

class PasswordResetLinkController extends Controller
{
    use NotifyTrait;

    /**
     * Display the password reset link request view.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        $page = Page::where('code', 'forgetpassword')->first();
        $data = json_decode($page->data, true);

        return view('frontend::auth.forgot-password', compact('data'));
    }

    /**
     * Handle an incoming password reset link request.
     *
     * @return \Illuminate\Http\RedirectResponse
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'email' => 'required|email|exists:users',
        ]);

        if ($validator->fails()) {
            notify()->error($validator->errors()->first(), 'Error');

            return redirect()->back();
        }

        $token = Str::random(64);

        DB::table('password_resets')->insert([
            'email' => $request->email,
            'token' => $token,
            'created_at' => Carbon::now(),
        ]);
        $user = User::where('email',$request->email)->first();

        $token = route('password.reset', ['token' => $token, 'email' => $request->email]);

        $shortcodes = [
            '[[full_name]]' => $user->full_name,
            '[[token]]' => $token,
            '[[site_title]]' => setting('site_title', 'global'),
            '[[site_url]]' => route('home'),
        ];

        $this->mailNotify($request->email, 'user_password_change', $shortcodes);
        notify()->success('We have emailed your password reset link!');

        return redirect()->back()->with('status', __('Some email providers may deliver this email to your Spam or Junk folder. Please check there if you do not see it in your inbox.'));

    }

    public function getPassword()
    {
        return view('frontend::auth.get-password');
    }

    public function sendPassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|exists:users',
        ]);

        if ($validator->fails()) {
            notify()->error($validator->errors()->first(), 'Error');
            return redirect()->back();
        }

        $password = $this->generateUniquePassword();

        $user = User::where('email', $request->email)->first();
        $user->password = Hash::make($password);
        $user->save();

        $shortcodes = [
            '[[site_password]]' => $password,
            '[[site_title]]' => setting('site_title', 'global'),
            '[[site_url]]' => route('home'),
        ];

        $this->mailNotify($request->email, 'user_password_send', $shortcodes);
        return redirect()->back()->with('status', __('We have emailed your new password!'));
    }

    private function generateUniquePassword()
    {
        do {
            $password = Str::random(8);
        } while (User::whereRaw("password = ?", [Hash::make($password)])->exists());

        return $password;
    }

}
