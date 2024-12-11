<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Social;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use DataTables;

class SocialController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        if ($request->ajax()) {

            $data = Social::latest()->get();

            return DataTables::of($data)
                ->addIndexColumn()

                ->addColumn('title', function ($row) {
                    return '<span class="text-nowrap">' . $row->title . '</span>';
                })
                ->addColumn('client_id', function ($row) {
                    return '<a href="' . $row->client_id . '" class="lowercase text-nowrap" target="_blank">' . $row->client_id . '</a>';
                })
                ->addColumn('client_secret', function ($row) {
                    return '<a href="' . $row->client_secret . '" class="lowercase text-nowrap" target="_blank">' . $row->client_secret . '</a>';
                })
                ->addColumn('status', 'backend.links.include.__status')
                ->addColumn('action', function ($row) {
                    return '<button type="button" class="action-btn editBtn" data-id="' . $row->id . '">
                                <iconify-icon icon="lucide:edit-3"></iconify-icon>
                            </button>';
                })
                ->rawColumns(['title', 'client_id','client_secret', 'status', 'action'])
                ->make(true);
        }

        return view('backend.setting.organization.social_login.index');
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
        $socialLogin = Social::find($id);
        return view('backend.setting.organization.social_login.__social_login_form', compact('socialLogin'))->render();
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
            'client_id' => 'required',
            'client_secret' => 'required',
            'redirect' => 'required',
            'status' => 'required',
        ]);

        if ($validator->fails()) {
            notify()->error($validator->errors()->first(), 'Error');
            return redirect()->back();
        }

        $input = $request->all();
        $data = [
            'title' => $input['title'],
            'client_id' => $input['client_id'],
            'client_secret' => $input['client_secret'],
            'redirect' => $input['redirect'],
            'status' => $input['status'],
        ];

        $socialLink = Social::find($input['id']);

        $socialLink->update($data);

        notify()->success('Social login update successfully');
        return redirect()->back();
    }
}
