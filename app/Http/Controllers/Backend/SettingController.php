<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use App\Traits\ImageUpload;
use Exception;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class SettingController extends Controller
{
    use ImageUpload;

    /**
     * Display a listing of the resource.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('permission:site-setting|email-setting', ['only' => ['update']]);
        $this->middleware('permission:site-setting', ['only' => ['siteSetting']]);
        $this->middleware('permission:email-setting', ['only' => ['mailSetting']]);

    }

    /**
     * @return Application|Factory|View
     */
    public static function siteSetting()
    {
        return view('backend.setting.site_setting.index');
    }

    /**
     * @return Application|Factory|View
     */
    public static function mailSetting()
    {
        return view('backend.setting.mail');
    }
    public static function forexApiSetting()
    {
        return view('backend.setting.forex-api');
    }

    public static function mailConnectionTest(Request $request)
    {

        try {
            Mail::raw('Testing SMTP connection successful', function ($message) use ($request) {
                $message->to($request->email);
            });

            notify()->success(__('SMTP connection test successful.'));

            return back();
        } catch (\Exception $e) {
            notify()->error('SMTP connection test failed: '.$e->getMessage(), 'Error');

            return redirect()->back();
        }

    }

    //store any setting

    /**
     * @return RedirectResponse
     */
    public function update(Request $request)
    {
//         dd($request->all());
        $section = $request->section;
        $rules = Setting::getValidationRules($section);
//        dd($request->all(),$rules);
        $data = $this->validate($request, $rules);

        try {
            $validSettings = array_keys($rules);
            foreach ($data as $key => $val) {

                if (in_array($key, $validSettings)) {
                    if ($request->hasFile($key)) {
                        $oldImage = Setting::get($key, $section);

                        $val = self::imageUploadTrait($val, $oldImage);
                    }
                    Setting::add($key, $val, Setting::getDataType($key, $section));
                }
            }
            notify()->success(__('Settings has been saved'));

            return redirect()->back();

        } catch (Exception $e) {
            notify()->error('Something went wrong, Please check error log', 'Error Log');

            return redirect()->back();
        }

    }

    public static function userPermissions()
    {
        return view('backend.setting.user_permissions.index');
    }

    public static function serverSetting()
    {
        return view('backend.server_settings.server.index');
    }

    public static function companySetting()
    {
        return view('backend.setting.site_setting.company');
    }

    public static function currencySetting()
    {
        return view('backend.setting.site_setting.currency');
    }

    public static function siteMaintenance()
    {
        return view('backend.setting.site_setting.site_maintenance');
    }

    public static function transfers()
    {
        return view('backend.setting.site_setting.transfers');
    }

    public static function gdpr()
    {
        return view('backend.setting.site_setting.gdpr');
    }

    public static function slackSetting()
    {
        return view('backend.setting.collab_tools.slack');
    }

}
