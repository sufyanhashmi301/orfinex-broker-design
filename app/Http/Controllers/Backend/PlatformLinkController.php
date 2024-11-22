<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PlatformLink;
use Illuminate\Support\Facades\Validator;
use DataTables;

class PlatformLinkController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        if ($request->ajax()) {

            $data = PlatformLink::latest()->get();

            return DataTables::of($data)
                ->addIndexColumn()

                ->addColumn('title', function ($row) {
                    return '<span class="text-nowrap">' . $row->title . '</span>';
                })
                ->addColumn('link', function ($row) {
                    return '<a href="' . $row->link . '" class="lowercase text-nowrap" target="_blank">' . $row->link . '</a>';
                })
                ->addColumn('status', 'backend.links.include.__status')
                ->addColumn('action', 'backend.links.include.__action')
                ->rawColumns(['title', 'link', 'status', 'action'])
                ->make(true);
        }

        return view('backend.links.platform');
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
        $input = $request->all();
        $validator = Validator::make($input, [
            'title' => 'required',
            'link' => 'required',
            'platform' => 'required',
            'status' => 'required',
        ]);

        if ($validator->fails()) {
            notify()->error($validator->errors()->first(), 'Error');
            return redirect()->back();
        }

        $data = [
            'title' => $input['title'],
            'link' => $input['link'],
            'platform' => $input['platform'],
            'status' => $input['status'],
        ];

        $platformLink = PlatformLink::create($data);
        notify()->success('Platform link created successfully');
        return redirect()->route('admin.links.platform.index');
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
        $platformLink = PlatformLink::find($id);
        return view('backend.links.include.__edit_platform_link_form', compact('platformLink'))->render();
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $input = $request->all();
        $validator = Validator::make($input, [
            'title' => 'required',
            'link' => 'required',
            'platform' => 'required',
            'status' => 'required',
        ]);

        if ($validator->fails()) {
            notify()->error($validator->errors()->first(), 'Error');
            return redirect()->back();
        }

        $input = $request->all();
        $data = [
            'title' => $input['title'],
            'link' => $input['link'],
            'platform' => $input['platform'],
            'status' => $input['status'],
        ];

        $platformLink = PlatformLink::find($input['id']);

        $platformLink->update($data);

        notify()->success('Platform link update successfully');
        return redirect()->route('admin.links.platform.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $platformLink = PlatformLink::find($id);
        $platformLink->delete();

        notify()->success(__('Platform link deleted successfully.'));
        return redirect()->route('admin.links.platform.index');
    }
}
