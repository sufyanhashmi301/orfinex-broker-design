<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use Illuminate\Http\Request;
use App\Traits\ImageUpload; 

class BannerController extends Controller
{
    use ImageUpload;
    
    public function __construct()
    {
        $this->middleware('permission:banner-settings', ['only' => ['index']]);
    
    }

    public function index()
    {
        $banners = Banner::all();
        return view('backend.setting.banner.index', compact('banners'));
    }

    public function update(Request $request, $id)
    {
        // Validate the request
        $request->validate([
            'title' => 'required|string|max:255',
            'subtitle' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,svg,webp|max:5120',
            'primary_link' => 'nullable|string',
            'button_text' => 'nullable|string|max:255',
            'button_link' => 'nullable|string',
            'status' => 'required|boolean',
        ]);

        // Find the banner by ID
        $banner = Banner::findOrFail($id);

        // Update all fields
        $banner->title = $request->input('title');
        $banner->subtitle = $request->input('subtitle');
        $banner->primary_link = $request->input('primary_link');
        $banner->button_text = $request->input('button_text');
        $banner->button_link = $request->input('button_link');
        $banner->status = $request->input('status');

        // Handle image upload
        if ($request->hasFile('image')) {
            $banner->image = self::imageUploadTrait($request->file('image'), $banner->image);
        }

        $banner->save();

        notify()->success(__('Banner updated successfully'));
        return redirect()->back();
    }

}
