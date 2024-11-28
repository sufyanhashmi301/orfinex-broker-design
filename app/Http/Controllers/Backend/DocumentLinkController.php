<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\DocumentLink;
use Illuminate\Support\Facades\Validator;
use DataTables;

class DocumentLinkController extends Controller
{

    public function __construct()
    {
        $this->middleware('permission:document-link-list', ['only' => ['index']]);
         $this->middleware('permission:document-link-create', ['only' => ['store']]);
         $this->middleware('permission:document-link-edit', ['only' => ['update']]);
         $this->middleware('permission:document-link-delete', ['only' => ['destroy']]);
    }
    

    public function index(Request $request)
    {

        if ($request->ajax()) {

            $data = DocumentLink::latest()->get();

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

        return view('backend.links.document');
    }

    public function store(Request $request)
    {
        $input = $request->all();
        $validator = Validator::make($input, [
            'title' => 'required',
            'link' => 'required',
            'status' => 'required',
        ]);

        if ($validator->fails()) {
            notify()->error($validator->errors()->first(), 'Error');
            return redirect()->back();
        }

        $data = [
            'title' => $input['title'],
            'link' => $input['link'],
            'status' => $input['status'],
        ];

        $documentLink = DocumentLink::create($data);
        notify()->success('Document link created successfully');
        return redirect()->route('admin.links.document.index');
    }

    public function edit($id)
    {
        $documentLink = DocumentLink::find($id);
        return view('backend.links.include.__edit_document_link_form', compact('documentLink'))->render();
    }

    public function update(Request $request)
    {
        $input = $request->all();
        $validator = Validator::make($input, [
            'title' => 'required',
            'link' => 'required',
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
            'status' => $input['status'],
        ];

        $documentLink = DocumentLink::find($input['id']);

        $documentLink->update($data);

        notify()->success('Document link update successfully');
        return redirect()->route('admin.links.document.index');
    }

    public function destroy($id)
    {
        $documentLink = DocumentLink::find($id);
        $documentLink->delete();

        notify()->success(__('Document link deleted successfully.'));
        return redirect()->route('admin.links.document.index');

    }

}
