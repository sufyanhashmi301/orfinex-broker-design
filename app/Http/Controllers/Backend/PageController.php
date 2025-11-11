<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PageSetting;
use App\Models\SuccessPage;
use App\Traits\ImageUpload;

class PageController extends Controller
{
    use ImageUpload;
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

    public function successPage()
    {
        $successPages = SuccessPage::all();
        return view('backend.setting.customization.dynamic_content.success-page', compact('successPages'));
    }

    public function successPageEdit($id)
    {
        $successPage = SuccessPage::findOrFail($id);
        return view('backend.setting.customization.dynamic_content.edit-success-page', compact('successPage'));
    }

    public function successPageUpdate(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|string',
            'title' => 'nullable|string|max:255',
            'subtitle' => 'nullable|string|max:255',
            'message' => 'nullable|string',
            'quote' => 'nullable|string',
            'quote_author' => 'nullable|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'button_text' => 'nullable|string|max:255',
            'button_link' => 'nullable|string|max:255',
            'button_type' => 'nullable|string|in:primary,secondary,outline',
            'quote_show' => 'nullable|boolean',
            'trustpilot_button_show' => 'nullable|boolean',
        ]);

        $successPage = SuccessPage::findOrFail($id);
        
        $input = $request->except(['_token', '_method', 'image']);
        $input['quote_show'] = $request->input('quote_show', 0);

        // Handle image upload
        if ($request->hasFile('image')) {
            $imagePath = self::successPageImageUploadTrait($request->file('image'), $successPage->image_path);
            $input['image_path'] = $imagePath;
        }

        // Convert trustpilot checkbox value
        $input['trustpilot_button_show'] = $request->input('trustpilot_button_show', 0);

        // Process route shortcodes
        if (!empty($input['button_link'])) {
            $input['button_link'] = $this->processRouteShortcode($input['button_link']);
        }

        $successPage->update($input);

        notify()->success(__('Success Page Updated Successfully'));

        return redirect()->back();
    }

    /**
     * Process route shortcodes to actual routes
     *
     * @param string $link
     * @return string
     */
    private function processRouteShortcode($link)
    {
        $routeMap = [
            '{{route.dashboard}}' => '/user/dashboard',
            '{{route.transactions}}' => '/user/history/transactions',
            '{{route.deposit}}' => '/user/deposit/methods',
            '{{route.withdraw}}' => '/user/withdraw',
            '{{route.profile}}' => '/user/settings/profile',
            '{{route.transfer}}' => '/user/transfer',
            '{{route.wallet}}' => '/user/wallet',
        ];

        return str_replace(array_keys($routeMap), array_values($routeMap), $link);
    }

}
