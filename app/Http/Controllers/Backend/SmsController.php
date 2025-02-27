<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\SmsTemplate;
use DataTables;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SmsController extends Controller
{

    public function __construct()
    {
        $this->middleware('permission:admin-sms-template', ['only' => ['template']]);
         $this->middleware('permission:user-sms-template', ['only' => ['userTemplate']]);
         $this->middleware('permission:sms-template-action', ['only' => ['edit_template']]);
    }

    public function template(Request $request)
    {

        if ($request->ajax()) {

            $data = SmsTemplate::query()->where('for', 'Admin')->latest();

            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('name', 'backend.sms.include.__name')
                ->addColumn('status', 'backend.sms.include.__status')
                ->addColumn('action', 'backend.sms.include.__action')
                ->rawColumns(['name', 'status', 'action'])
                ->make(true);
        }

        return view('backend.sms.admin_template');
    }

    public function userTemplate(Request $request)
    {

        if ($request->ajax()) {

            $data = SmsTemplate::query()->where('for', 'User')->latest();

            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('name', 'backend.sms.include.__name')
                ->addColumn('status', 'backend.sms.include.__status')
                ->addColumn('action', 'backend.sms.include.__action')
                ->rawColumns(['name', 'status', 'action'])
                ->make(true);
        }

        return view('backend.sms.user_template');
    }

    public function edit_template($id)
    {
        $template = SmsTemplate::find($id);

        return view('backend.sms.edit', compact('template'));
    }

    public function update_template(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'message_body' => 'required',
        ]);

        if ($validator->fails()) {
            notify()->error($validator->errors()->first(), 'Error');

            return redirect()->back();
        }

        $input = $request->all();
        $input['message_body'] = str_replace(['{', '}'], ['<', '>'], $request->message_body);

        $data = [
            'message_body' => htmlspecialchars_decode($input['message_body']),
            'status' => $input['status'],
        ];

        $template = SmsTemplate::find($input['id']);

        $template->update($data);

        notify()->success(__('SMS Template Updated Successfully'));

        return redirect()->back();
    }
}
