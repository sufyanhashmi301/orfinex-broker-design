<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Subscription;
use App\Models\User;
use App\Rules\MatchOldPassword;
use App\Traits\ImageUpload;
use App\Traits\NotifyTrait;
use DataTables;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AppController extends Controller
{
    use ImageUpload, NotifyTrait;

    public function __construct()
    {
        $this->middleware('permission:subscriber-list|subscriber-mail-send', ['only' => ['subscribers']]);
        $this->middleware('permission:subscriber-mail-send', ['only' => ['mailSendSubscriber', 'mailSendSubscriberNow']]);
        $this->middleware('permission:application-details-settings', ['only' => ['applicationInfo']]);
        $this->middleware('permission:changelog-settings', ['only' => ['changeLog']]);
        $this->middleware('permission:report-issue-settings', ['only' => ['reportIssue']]);
    }

    public function subscribers(Request $request)
    {
        if ($request->ajax()) {
            $data = Subscription::query();

            return Datatables::of($data)
                ->addIndexColumn()
                ->make(true);
        }

        return view('backend.subscriber.index');
    }

    public function mailSendSubscriber()
    {
        return view('backend.subscriber.mail_send');
    }

    public function mailSendSubscriberNow(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'subject' => 'required',
            'message' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator->errors())->withInput();
        }

        try {
            $input = [
                'subject' => $request->subject,
                'message' => $request->message,
            ];

            $input['message'] = str_replace(['{', '}'], ['<', '>'], $request->message);

            $shortcodes = [
                '[[subject]]' => $input['subject'],
                '[[message]]' => $input['message'],
                '[[site_title]]' => setting('site_title', 'global'),
                '[[site_url]]' => route('home'),
            ];

            $subscribers = User::all();
            foreach ($subscribers as $subscriber) {
                $this->mailNotify($subscriber->email, 'subscriber_mail', $shortcodes);
            }
            $status = 'success';
            $message = __('Mail Send Successfully');
        } catch (Exception $e) {
            $status = 'warning';
            $message = __('something is wrong');
        }

        notify()->$status($message, $status);

        return redirect()->back();
    }

    public function profile()
    {
        return view('backend.profile.profile');
    }

    public function profileUpdate(Request $request)
    {
        // dd($request->all());
        $user = \Auth::user();
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email|unique:admins,email,'.$user->id,

        ]);

        if ($validator->fails()) {
            notify()->error($validator->errors()->first(), 'Error');

            return redirect()->back();
        }
        auth()->user()->update([
            'avatar' => $request->hasFile('avatar') ? self::imageUploadTrait($request->avatar, $user->avatar) : $user->avatar,
            'phone' => $request->phone,
        ]);
        notify()->success('Profile Update Successfully');

        return redirect()->back();
    }

    public function passwordChange()
    {
        return view('backend.profile.password_change');
    }

    public function passwordUpdate(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'current_password' => ['required', new MatchOldPassword],
            'new_password' => ['required'],
            'new_confirm_password' => ['same:new_password'],
        ]);

        if ($validator->fails()) {
            notify()->error($validator->errors()->first(), 'Error');

            return redirect()->back();
        }

        auth()->user()->update(['password' => Hash::make($request->new_password)]);
        notify()->success('Password Changed Successfully');

        return redirect()->back();
    }

    public function applicationInfo()
    {

        $applicationInfo = [
            'PHP Version' => 8.1,
            'Laravel Version' => 9.3,
            'Site Name' => setting('site_title', 'global'),
            'Debug Mode' => config('app.debug') ? 'Enabled' : 'Disabled',
            'Site Mode' => config('app.env') == 'local' ? 'Testing' : 'Production',
            'Database Port' => config('database.connections.mysql.port'),
        ];

        return view('backend.system.index', compact('applicationInfo'));
    }

    public function clearCache()
    {
        notify()->success('Clear Cache Successfully');
        \Artisan::call('optimize:clear');

        return redirect()->back();
    }

    public function changeLog()
    {
        return view('backend.system.changelog');
    }

    public function reportIssue()
    {
        return view('backend.system.report_issues');
    }

}
