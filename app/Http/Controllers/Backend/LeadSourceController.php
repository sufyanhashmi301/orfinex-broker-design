<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\LeadSource;
use App\Services\LeadSourceService;
use Illuminate\Http\Request;

class LeadSourceController extends Controller
{
    protected $leadSourceService;

    public function __construct(LeadSourceService $leadSourceService)
    {
        $this->middleware('permission:lead-source-list', ['only' => ['index']]);
         $this->middleware('permission:lead-source-create', ['only' => ['store']]);
         $this->middleware('permission:lead-source-edit', ['only' => ['update']]);
         $this->middleware('permission:lead-source-delete', ['only' => ['destroy']]);
        $this->leadSourceService = $leadSourceService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $sources = $this->leadSourceService->getAll();
        return view('backend.setting.lead.source.index', compact('sources'));
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
        $data = $request->validate([
            'name' => 'required|string|max:255',
        ]);

        // Check if the status name already exists
        $existingSource = LeadSource::where('name', $request->name)->first();

        if ($existingSource) {
            notify()->error(__('The source name already exists.'));
            return redirect()->back();
        }

        $this->leadSourceService->create($data);
        notify()->success(__('Lead source created successfully.'));
        return redirect()->route('admin.lead.source.index');
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
        $source = $this->leadSourceService->getById($id);
        return view('backend.setting.lead.source.include.__edit_form', compact('source'))->render();
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
        $data = $request->validate([
            'name' => 'required|string|max:255',
        ]);

        // Check if the status name already exists
        $existingSource = LeadSource::where('name', $request->name)->first();

        if ($existingSource) {
            notify()->error(__('The source name already exists.'));
            return redirect()->back();
        }

        $this->leadSourceService->update($id, $data);
        notify()->success(__('Lead source updated successfully.'));
        return redirect()->route('admin.lead.source.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $this->leadSourceService->delete($id);
        notify()->success(__('Lead source deleted successfully.'));
        return redirect()->route('admin.lead.source.index');
    }
}
