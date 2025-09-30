<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\EmailTemplate;
use App\Traits\ImageUpload;
use DataTables;
use Illuminate\Http\Request;
use Validator;
use App\Traits\NotifyTrait;

class EmailTemplateController extends Controller
{
    use ImageUpload, NotifyTrait;

    public function __construct()
    {
        $this->middleware('permission:admin-email-template', ['only' => ['index', 'store']]);
         $this->middleware('permission:user-email-template', ['only' => ['userTemplate']]);
         $this->middleware('permission:email-template-action', ['only' => ['edit','update']]);
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

        $message_body = $request->input('html_message_body');
        $bottom_body = $request->input('html_bottom_body');
//dd($message_body);
//        dd(str_replace(['{', '}'], ['<', '>'], $message_body));
        $validator = Validator::make($request->all(), [
            'subject' => 'required',
            'message_body' => 'required',
        ]);

        if ($validator->fails()) {
            notify()->error($validator->errors()->first(), 'Error');

            return redirect()->back();
        }

        $input = $request->all();
//        dd($input);
        $input['message_body'] = str_replace(['{', '}'], ['<', '>'], $message_body);
        $input['bottom_body'] = str_replace(['{', '}'], ['<', '>'], $bottom_body);
//        dd($input);
        $data = [
            'subject' => $input['subject'],
            'message_body' => htmlspecialchars_decode($input['message_body']),
            'title' => $input['title'],
            'button_level' => $input['button_level'],
            'button_link' => $input['button_link'],
            'footer_status' => $input['footer_status'] ?? 0,
            'bottom_status' => $input['bottom_status'] ?? 0,
            'bottom_title' => $input['bottom_title'] ?? null,
            'bottom_body' => htmlspecialchars_decode($input['bottom_body']) ?? null,
            'status' => $input['status'] ?? 0,
            'is_disclaimer' => $input['is_disclaimer'] ?? 0,
            'is_risk_warning' => $input['is_risk_warning'] ?? 0,
            'use_custom_html' => $input['use_custom_html'] ?? 0,
            'custom_html_content' => $input['custom_html_content'] ?? null,
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

    public function templateSetting(){
        return view('backend.email.settings');
    }
    public function testTemplate(Request $request)
{
    $request->validate([
        'email' => 'required|email',
        'template_id' => 'required|exists:email_templates,id'
    ]);

    $template = EmailTemplate::find($request->template_id);
    
    // Prepare shortcodes with test data
    $shortcodes = [];
    foreach (json_decode($template->short_codes) as $code) {
        $cleanCode = str_replace(['[[', ']]'], '', $code);
        $shortcodes[$code] = 'Test ' . str_replace('_', ' ', $cleanCode);
    }
    
    // Add some common shortcodes
    $shortcodes = array_merge($shortcodes, [
        '[[site_title]]' => setting('site_title', 'global'),
        '[[site_url]]' => route('home'),
    ]);

    try {
        // Use your mailNotify function to send the test email
        $this->mailNotify($request->email, $template->code, $shortcodes);

        notify()->success(__('Test email sent successfully!'));
        return redirect()->back();
    } catch (\Exception $e) {
        notify()->error(__('Failed to send test email: ') . $e->getMessage());
        return redirect()->back();
    }
}
}
