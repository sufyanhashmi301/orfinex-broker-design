<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\LeadStage;
use App\Services\LeadStageService;
use Illuminate\Http\Request;

class LeadStageController extends Controller
{

    protected $leadStageService;

    public function __construct(LeadStageService $leadStageService)
    {
        $this->leadStageService = $leadStageService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $stages = $this->leadStageService->getAll();
        return view('backend.setting.lead.stage.index', compact('stages'));
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
            'label_color' => 'required|string|max:255',
        ]);

        // Check if the status name already exists
        $existingStage = LeadStage::where('name', $request->name)->first();

        if ($existingStage) {
            notify()->error(__('The stage name already exists.'));
            return redirect()->back();
        }

        $this->leadStageService->create($data);
        notify()->success(__('Lead stage created successfully.'));
        return redirect()->route('admin.lead.stage.index');
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
        $stage = $this->leadStageService->getById($id);
        return view('backend.setting.lead.stage.include.__edit_form', compact('stage'))->render();
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
        $leadStage = LeadStage::findOrFail($id);

        // Check if the stage is 'Win' or 'Lose'
        if (in_array($leadStage->name, ['Win', 'Lose'])) {
            notify()->error(__('You cannot update the "Win" or "Lose" lead stages.'));
            return redirect()->route('admin.lead.stage.index');
        }

        $data = $request->validate([
            'name' => 'required|string|max:255',
            'label_color' => 'required|string|max:255',
        ]);

        $this->leadStageService->update($id, $data);
        notify()->success(__('Lead stage updated successfully.'));
        return redirect()->route('admin.lead.stage.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $leadStage = LeadStage::findOrFail($id);

        // Check if the stage is 'Win' or 'Lose'
        if (in_array($leadStage->name, ['Win', 'Lose'])) {
            notify()->error(__('You cannot delete the "Win" or "Lose" lead stages.'));
            return redirect()->route('admin.lead.stage.index');
        }

        $this->leadStageService->delete($id);
        notify()->success(__('Lead stage deleted successfully.'));
        return redirect()->route('admin.lead.stage.index');
    }
}
