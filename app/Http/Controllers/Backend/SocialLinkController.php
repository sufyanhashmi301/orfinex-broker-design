<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SocialLink;
use DataTables;
use Illuminate\Support\Facades\Validator;


class SocialLinkController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        if ($request->ajax()) {

            $data = SocialLink::latest()->get();

            return DataTables::of($data)
                ->addIndexColumn()

                ->addColumn('title', function ($row) {
                    return '<span class="text-nowrap">' . $row->title . '</span>';
                })
                ->addColumn('link', function ($row) {
                    return '<a href="' . $row->link . '" class="lowercase text-nowrap" target="_blank">' . $row->link . '</a>';
                })
                ->addColumn('status', 'backend.links.include.__status')
                ->addColumn('action', function ($row) {
                    return '<button type="button" class="action-btn editBtn" data-id="' . $row->id . '">
                                <iconify-icon icon="lucide:edit-3"></iconify-icon>
                            </button>';
                })
                ->rawColumns(['title', 'link', 'status', 'action'])
                ->make(true);
        }

        return view('backend.links.social');
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
        //
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
        $socialLink = SocialLink::find($id);
        return view('backend.links.include.__edit_social_link_form', compact('socialLink'))->render();
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
            'link' => 'required',
            'status' => 'required',
        ]);

        if ($validator->fails()) {
            notify()->error($validator->errors()->first(), 'Error');
            return redirect()->back();
        }

        $input = $request->all();
        $data = [
            'link' => $input['link'],
            'status' => $input['status'],
        ];

        $socialLink = SocialLink::find($input['id']);

        $socialLink->update($data);

        notify()->success('Social link update successfully');
        return redirect()->route('admin.links.social.index');
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
