<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PageSetting;

class PageController extends Controller
{
    public function pageSetting()
    {
        return view('backend.page.setting');
    }

    /**
     * @return RedirectResponse
     */
    public function pageSettingUpdate(Request $request)
    {

        $input = $request->except('_token');
        foreach ($input as $key => $value) {
            if ($request->hasFile($key)) {
                $value = self::imageUploadTrait($value, getPageSetting($key));
            }
            $this->settingUpdate($key, $value);
        }

        notify()->success(__('Page Setting Update Successfully'));

        return redirect()->back();
    }

    /**
     * @return void
     */
    private function settingUpdate($key, $value)
    {
        PageSetting::updateOrCreate(['key' => $key], ['value' => $value]);
    }

}
