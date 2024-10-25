<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\EmailTemplate;
use App\Traits\ImageUpload;
use DataTables;
use Illuminate\Http\Request;
use Validator;

class EmailTemplateController extends Controller
{
    use ImageUpload;

    public function __construct()
    {
        $this->middleware('permission:email-template');
    }

    public function index(Request $request)
    {

        if ($request->ajax()) {

            $data = EmailTemplate::query()->where('for', 'Admin')->orderBy('name','asc');

            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('name', 'backend.email.include.__name')
                ->addColumn('status', 'backend.email.include.__status')
                ->addColumn('action', 'backend.email.include.__action')
                ->rawColumns(['name', 'status', 'action'])
                ->make(true);
        }

        return view('backend.email.admin_template');
    }

    public function userTemplate(Request $request)
    {

        if ($request->ajax()) {

            $data = EmailTemplate::query()->where('for', 'User')->orderBy('name','asc');

            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('name', 'backend.email.include.__name')
                ->addColumn('status', 'backend.email.include.__status')
                ->addColumn('action', 'backend.email.include.__action')
                ->rawColumns(['name', 'status', 'action'])
                ->make(true);
        }

        return view('backend.email.user_template');
    }

    public function create()
    {
        return view('backend.email.create');
    }

    public function edit($id)
    {
        $template = EmailTemplate::find($id);
//        dd($template);

        return view('backend.email.edit', compact('template'));
    }

    public function update(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'subject' => 'required',
            'message_body' => 'required',
        ]);

        if ($validator->fails()) {
            notify()->error($validator->errors()->first(), 'Error');

            return redirect()->back();
        }

        $input = $request->all();
        $data = [
            'subject' => $input['subject'],
            'message_body' => nl2br($input['message_body']),
            'title' => $input['title'],
            'button_level' => $input['button_level'],
            'button_link' => $input['button_link'],
            'footer_status' => $input['footer_status'] ?? 0,
            'bottom_status' => $input['bottom_status'] ?? 0,
            'bottom_title' => $input['bottom_title'] ?? null,
            'bottom_body' => nl2br($input['bottom_body']) ?? null,
            'status' => $input['status'] ?? 0,
        ];

        $template = EmailTemplate::find($input['id']);
//        dd($input,$data);
        if (isset($input['banner']) && is_file($input['banner'])) {
            $data['banner'] = self::imageUploadTrait($input['banner'], $template->banner);
        }
//dd($data);
        $template->update($data);

        notify()->success(__('Email Template Updated Successfully'));
        if($template->for == 'User'){
            return redirect()->route('admin.email-template.user');
        }

        return redirect()->route('admin.email-template');
    }
}
