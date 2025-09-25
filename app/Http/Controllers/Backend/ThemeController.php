<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use App\Models\Theme;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;
use ZipArchive;
use App\Models\Setting;
use Exception;
use App\Traits\ImageUpload;
use Illuminate\Support\Facades\Artisan;

class ThemeController extends Controller
{
    use ImageUpload;

    public function __construct()
    {
        $this->middleware('permission:theme-settings', ['only' => ['siteTheme']]);
        $this->middleware('permission:branding-settings', ['only' => ['globalSetting']]);
        $this->middleware('permission:provider-logo-settings', ['only' => ['providerLogo']]);
        $this->middleware('permission:admin-auth-logo-settings', ['only' => ['adminAuthLogo']]);
        $this->middleware('permission:auth-covers-settings', ['only' => ['authCovers']]);
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

    public function providerLogo(Request $request)
    {
        return view('backend.setting.branding.provider_logo', [
            'section' => 'provider_logo',
            'fields' => config('setting.provider_logo')
        ]);
    }

    public function adminAuthLogo(Request $request)
    {
        return view('backend.setting.branding.admin_auth_logo', [
            'section' => 'admin_auth_logo',
            'fields' => config('setting.admin_auth_logo')
        ]);
    }

    public function authCovers()
    {
        $currentLoginBg = setting('login_bg', 'theme');
        $defaultLoginBg = 'https://cdn.brokeret.com/crm-assets/login-image/c19.png';
        $currentChoice = setting('login_bg_choice', 'theme', 'default');
        
        // Check if there's a custom cover uploaded (not default URLs)
        $hasCustomCover = !empty($currentLoginBg) && 
                         $currentLoginBg !== $defaultLoginBg && 
                         $currentLoginBg !== 'default/auth-bg.jpg' &&
                         $currentLoginBg !== 'https://cdn.brokeret.com/crm-assets/login-image/c19.png';
        
        return view('backend.setting.branding.auth_covers', compact('currentLoginBg', 'defaultLoginBg', 'hasCustomCover', 'currentChoice'));
    }

    public function updateAuthCovers(Request $request)
    {
        $request->validate([
            'login_bg_choice' => 'required|in:default,uploaded',
            'show_login_logo' => 'boolean',
            'login_bg' => 'nullable|image|mimes:jpeg,jpg,png|max:2048'
        ]);

        // Additional validation for custom cover upload
        if ($request->login_bg_choice === 'uploaded') {
            // Check if there's already a custom cover
            $existingCustomCover = setting('login_bg', 'theme');
            $defaultUrls = ['https://cdn.brokeret.com/crm-assets/login-image/c19.png', 'default/auth-bg.jpg'];
            $hasExistingCustomCover = !empty($existingCustomCover) && !in_array($existingCustomCover, $defaultUrls);
            
            // Only require file upload if no existing custom cover
            if (!$hasExistingCustomCover) {
                $request->validate([
                    'login_bg' => 'required|image|mimes:jpeg,jpg,png|max:2048'
                ], [
                    'login_bg.required' => __('Please upload a custom cover image when selecting custom cover option.'),
                    'login_bg.image' => __('The uploaded file must be an image.'),
                    'login_bg.mimes' => __('The image must be a JPEG, JPG, or PNG file.'),
                    'login_bg.max' => __('The image size must not exceed 2MB.')
                ]);
            } else {
                // If existing custom cover, file upload is optional
                $request->validate([
                    'login_bg' => 'nullable|image|mimes:jpeg,jpg,png|max:2048'
                ], [
                    'login_bg.image' => __('The uploaded file must be an image.'),
                    'login_bg.mimes' => __('The image must be a JPEG, JPG, or PNG file.'),
                    'login_bg.max' => __('The image size must not exceed 2MB.')
                ]);
            }
        }

        // Handle show_login_logo setting
        $showLoginLogo = $request->has('show_login_logo') && $request->show_login_logo == '1' ? 1 : 0;
        Setting::add('show_login_logo', $showLoginLogo, 'theme');

        // Store the current choice
        Setting::add('login_bg_choice', $request->login_bg_choice, 'theme');

        // Handle login background choice
        if ($request->login_bg_choice === 'default') {
            // Keep the custom cover in database, just change the choice
            // The frontend will handle showing default based on the choice
        } elseif ($request->login_bg_choice === 'uploaded') {
            // Handle file upload if provided
            if ($request->hasFile('login_bg')) {
                $file = $request->file('login_bg');
                $oldImage = setting('login_bg', 'theme');
                $path = $this->imageUploadTrait($file, $oldImage);
                Setting::add('login_bg', $path, 'theme');
            } else {
                // If no file uploaded but custom is selected, use existing custom cover
                $existingCustomCover = setting('login_bg', 'theme');
                $defaultUrls = ['https://cdn.brokeret.com/crm-assets/login-image/c19.png', 'default/auth-bg.jpg'];
                
                if (!empty($existingCustomCover) && !in_array($existingCustomCover, $defaultUrls)) {
                    // Keep existing custom cover
                    Setting::add('login_bg', $existingCustomCover, 'theme');
                } else {
                    // No custom cover available, remove setting (will show default)
                    Setting::where('key', 'login_bg')->where('group', 'theme')->delete();
                }
            }
        }

        // Clear cache for immediate reflection
        Artisan::call('config:clear');
        Artisan::call('cache:clear');

        notify()->success(__('Auth covers updated successfully!'));
        return redirect()->back();
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
