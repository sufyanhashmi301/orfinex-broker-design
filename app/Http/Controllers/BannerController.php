<?php

namespace App\Http\Controllers;

use App\Models\Banner;
use Illuminate\Support\Str;
use Illuminate\Http\Request;

class BannerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function userDashboard()
    {
        $banners = Banner::where('type', 'user_dashboard')->get();
        return view('backend.setting.promotion.banner.user_dashboard', compact('banners'));
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
    public function store(Request $request) {

        $list = [];
        if($request->list != '') {
            $list = explode("\r\n", $request->list);
        }
        
        $banner = New Banner();
        $banner->type = $request->type;
        $banner->title = $request->title;
        $banner->subtitle = $request->subtitle;
        $banner->description = $request->description;
        $banner->list = $list;
        $banner->button_text = $request->button_text;
        $banner->button_link = $request->button_link;
        $banner->status = $request->status;
        $banner->save();

        
        notify()->success('Banner Created Successfully!');
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
        $banner = Banner::findOrFail($id);

        $list = [];
        if($request->list != '') {
            $list = explode("\r\n", $request->list);
        }

        $banner->type = $request->type;
        $banner->title = $request->title;
        $banner->subtitle = $request->subtitle;
        $banner->description = $request->description;
        $banner->list = $list;
        $banner->button_text = $request->button_text;
        $banner->button_link = $request->button_link;
        $banner->status = $request->status;
        $banner->save();
        
        notify()->success('Banner Updated Successfully!');
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
        //
    }
}
