<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Database\QueryException;
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
        try {

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
                'slug' => strtolower(str_replace(' ', '_', $input['title'])),
                'is_deleteable' => $input['is_deleteable'],
                'status' => $input['status'],
            ];

            $documentLink = DocumentLink::create($data);

            // Sync with settings table based on slug
            $this->syncDocumentLinkWithSettings($documentLink, $input);

            notify()->success('Document link created successfully');
            return redirect()->route('admin.links.document.index');

        } catch (QueryException $e) {
            if ($e->getCode() == '23000' && strpos($e->getMessage(), 'document_links_slug_unique') !== false) {
                notify()->error(__('Please choose a different title.'));
            } else {
                // General database error handling
                notify()->error(__('An error occurred while processing your request.'));
            }
            return redirect()->route('admin.links.document.index');
        }
    }

    public function edit($id)
    {
        $documentLink = DocumentLink::find($id);
        return view('backend.links.include.__edit_document_link_form', compact('documentLink'))->render();
    }

    public function update(Request $request)
    {
        try {
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

            if (!$documentLink) {
                notify()->error('Document link not found');
                return redirect()->route('admin.links.document.index');
            }

            // Update the document link
            $documentLink->update($data);

            // Sync with settings table based on slug
            $this->syncDocumentLinkWithSettings($documentLink, $input);

            notify()->success('Document link update successfully');
            return redirect()->route('admin.links.document.index');
            
        } catch (\Exception $e) {
            notify()->error(__('An error occurred while updating the document link.'));
            return redirect()->route('admin.links.document.index');
        }
    }

    /**
     * Sync document link status and URL with settings table
     *
     * @param DocumentLink $documentLink
     * @param array $input
     * @return void
     */
    private function syncDocumentLinkWithSettings(DocumentLink $documentLink, array $input)
    {
        // Map document link slugs to their corresponding setting names
        $settingMap = [
            'ib_partner_agreement' => [
                'link' => 'IB_partner_agreement_link',
                'show' => 'IB_partner_agreement_show',
            ],
            'aml_policy' => [
                'link' => 'aml_policy_link',
                'show' => 'aml_policy_show',
            ],
            'cookies_policy' => [
                'link' => 'cookies_policy_link',
                'show' => 'cookies_policy_show',
            ],
            'order_execution_policy' => [
                'link' => 'order_execution_policy_link',
                'show' => 'order_execution_policy_show',
            ],
            'privacy_policy' => [
                'link' => 'privacy_policy_link',
                'show' => 'privacy_policy_show',
            ],
            'risk_disclosure' => [
                'link' => 'risk_disclosure_link',
                'show' => 'risk_disclosure_show',
            ],
            'terms_and_conditions' => [
                'link' => 'terms_and_conditions_link',
                'show' => 'terms_and_conditions_show',
            ],
        ];

        // Check if this document link has corresponding settings
        if (isset($settingMap[$documentLink->slug])) {
            $settings = $settingMap[$documentLink->slug];

            // Update the link setting
            \App\Models\Setting::set($settings['link'], $input['link'], 'string');

            // Update the show/status setting (convert to boolean)
            \App\Models\Setting::set($settings['show'], $input['status'], 'boolean');
        }
    }

    public function destroy($id)
    {
        try {
            $documentLink = DocumentLink::find($id);

            if ($documentLink && $documentLink->is_deleteable == 1) {

                // Map document link slugs to their corresponding setting names
                $settingMap = [
                    'ib_partner_agreement' => 'IB_partner_agreement_show',
                    'aml_policy' => 'aml_policy_show',
                    'cookies_policy' => 'cookies_policy_show',
                    'order_execution_policy' => 'order_execution_policy_show',
                    'privacy_policy' => 'privacy_policy_show',
                    'risk_disclosure' => 'risk_disclosure_show',
                    'terms_and_conditions' => 'terms_and_conditions_show',
                ];

                // If this document link has a corresponding setting, disable it
                if (isset($settingMap[$documentLink->slug])) {
                    \App\Models\Setting::set($settingMap[$documentLink->slug], 0, 'boolean');
                }

                $documentLink->delete();
                notify()->success(__('Document link deleted successfully.'));

            } else {

                notify()->error(__('This document link cannot be deleted.'));

            }

            return redirect()->route('admin.links.document.index');
            
        } catch (\Exception $e) {
            notify()->error(__('An error occurred while deleting the document link.'));
            return redirect()->route('admin.links.document.index');
        }

    }

}
