<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use App\Models\Theme;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;
use ZipArchive;
class ThemeController extends Controller
{


    public function __construct()
    {
        $this->middleware('permission:theme-settings', ['only' => ['siteTheme']]);
        $this->middleware('permission:branding-settings', ['only' => ['globalSetting']]);
    }

    public function siteTheme()
    {
        $themes = Theme::where('type', 'site')->get();
        $banners = Banner::all();

        return view('backend.theme.template', compact('themes', 'banners'));
    }

    public function globalSetting()
    {
        return view('backend.theme.global');
    }

    public function popup()
    {
        return view('backend.setting.banner.popup');
    }

    public function companyLogo(Request $request)
{
    return view('backend.setting.branding.company_logo', [
        'section' => 'company_logo',
        'fields' => config('setting.company_logo')
    ]);
}
    public function colorsSetting(Request $request)
    {
        // Retrieve the 'type' query parameter from the request
        $type = $request->query('type');
        // Pass the 'type' variable to the view
        return view('backend.theme.colors', compact('type'));
    }

    public function fontSetting()
    {
        return view('backend.theme.fonts');
    }

    public function dynamicLanding()
    {
        $landingThemes = Theme::where('type', 'landing')->get();

        return view('backend.theme.dynamic_landing', compact('landingThemes'));
    }

    public function statusUpdate(Request $request)
    {
        $input = $request->all();

        $theme = Theme::find($input['id']);

        $status = $theme->type == 'site' ? 1 : $input['status'];

        if ($status) {
            $query = Theme::where('type', $theme->type)->where('status', true);
            $oldStatus = $query->pluck('id')->toArray();
            $query->update([
                'status' => 0,
            ]);
        }
        $theme->update([
            'status' => $status,
        ]);

        if ($theme->type == 'site') {
            notify()->success(__('Site Theme Status Updated Successfully'));

            return redirect()->back();

        }

        return response()->json([
            'old_status' => $oldStatus ?? [],
            'message' => __('Landing Theme Status Updated Successfully'),
        ]);

    }

    public function dynamicLandingUpdate(Request $request)
    {

        $input = $request->all();

        $zipThemeFile = $input['theme_file'];
        $themeFileName = $zipThemeFile->getClientOriginalName();
        $themeFileName = pathinfo($themeFileName, PATHINFO_FILENAME);

        $input = array_merge($input, [
            'name' => $themeFileName,
        ]);

        $validator = Validator::make($input, [
            'theme_file' => 'required|file|mimes:zip|max:30048',
            'name' => 'required|unique:themes', // Replace "table_name" with the actual table name
        ]);

        if ($validator->fails()) {
            notify()->error($validator->errors()->first(), 'Error');

            return redirect()->back();
        }

        $zip = new ZipArchive;
        if ($zip->open($zipThemeFile) !== true) {
            // Handle zip file opening failure
            notify()->error('Failed to open the zip file', 'Error');

            return redirect()->back();
        }

        $indexHtmlExists = false;
        for ($i = 0; $i < $zip->numFiles; $i++) {
            $filename = $zip->getNameIndex($i);
            if ($filename === 'index.html') {
                $indexHtmlExists = true;
                break;
            }
        }
        if (! $indexHtmlExists) {
            // Handle index.html not found
            notify()->error('The zip file does not contain index.html', 'Error');

            return redirect()->back();
        }

        $zip->extractTo('./assets/landing_theme/'.$themeFileName);
        $zip->close();
        $themeHtml = file_get_contents("./assets/landing_theme/$themeFileName/index.html");
        file_put_contents("./resources/views/landing_theme/$themeFileName.blade.php", $themeHtml);
        @unlink("assets/landing_theme/$themeFileName/index.html");
        Theme::create([
            'name' => $themeFileName,
            'type' => 'landing',
            'status' => false,
        ]);

        notify()->success(__('Landing Theme Uploaded Successfully'));

        return redirect()->back();
    }

    public function dynamicLandingStatusUpdate(Request $request)
    {
        $id = $request->id;
        $status = $request->status;

        $theme = Theme::find($id);

        if ($status) {
            $query = Theme::where('type', 'landing')->where('status', true);
            $oldStatus = $query->pluck('id')->toArray();
            $query->update([
                'status' => 0,
            ]);
        }
        $theme->update([
            'status' => $status,
        ]);

    }

    public function dynamicLandingDelete($id)
    {
        $theme = Theme::find($id);
        File::deleteDirectory("assets/landing_theme/$theme->name");
        if (file_exists(resource_path("views/landing_theme/$theme->name.blade.php"))) {
            unlink(resource_path("views/landing_theme/$theme->name.blade.php"));
        }
        $theme->delete();
        notify()->success(__('Landing Theme Deleted Successfully'));

        return redirect()->back();
    }
}
