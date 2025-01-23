<?php

namespace App\Http\Controllers;

use App\Models\Slider;
use App\Traits\ImageUpload;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SliderController extends Controller
{

    use ImageUpload;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function userDashboard()
    {
        $sliders = Slider::orderBy('status', 'DESC')->get();
        return view('backend.setting.promotion.slider.user_dashboard', compact('sliders'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Basic Validation
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'status' => 'required|in:enabled,disabled',
            'slides' => 'array|min:1|max:10|required', 
        ]);

        if ($validator->fails()) {
            notify()->error($validator->errors()->first(), 'Error Creating Slider');
            return redirect()->back();
        }

        // Process Slides
        $slides = [];
        foreach($request->slides as $slide) {
            $uploaded_slide =  $this->imageUploadTrait($slide, null, $request->type . '_slider_');
            array_push($slides, $uploaded_slide);
        }

        $slider = New Slider();
        $slider->name = $request->name;
        $slider->type = $request->name;
        $slider->slides = $slides;
        $slider->status = $request->status;
        $slider->save();

        // if the new slider is enabled then inactive all
        if($request->status == 'enabled') {
            Slider::where('id', '!=', $slider->id)->update(['status' => 'disabled']);
        }

        notify()->success('Slider Created Successfully.');
        return redirect()->back();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $slider = Slider::findOrFail($id);

        if($request->action == 'activate') {
            Slider::where('id', '!=', $id)->update(['status' => 'disabled']);
            $slider->update(['status' => 'enabled']);
            notify()->success('Slider Activated Successfully');
        }

        if($request->action == 'deactivate') {
            $slider->update(['status' => 'disabled']);
            notify()->success('Slider Deactivated Successfully');
        }

        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $slider = Slider::findOrFail($id);

        foreach($slider->slides as $slide) {
            $this->delete($slide);
        }

        $slider->delete();

        notify()->success('Slider Deleted Successfully.');
        return redirect()->back();
    }
}
