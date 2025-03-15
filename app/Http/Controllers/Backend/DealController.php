<?php

namespace App\Http\Controllers\Backend;

use App\Helper\Reply;
use App\Http\Controllers\Controller;
use App\Models\LeadPipeline;
use App\Models\PipelineStage;
use App\Models\Deal;
use App\Models\Lead;
use Illuminate\Http\Request;

class DealController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $loggedInUser = auth()->user();
        $pipelinesbox = LeadPipeline::get();

        $selectedPipelineId = $request->get('pipeline_id');

        if ($selectedPipelineId) {
            $pipeline = LeadPipeline::with(['stages.deals.lead'])
                ->where('id', $selectedPipelineId)
                ->first();
        } else {
            $pipeline = LeadPipeline::with(['stages.deals.lead'])
                ->where('default', true)
                ->first();
        }

        if (!$loggedInUser->hasRole('Super-Admin')){
            $pipeline->stages->each(function($stage) use ($loggedInUser) {
                $stage->deals = $stage->deals->filter(function($deal) use ($loggedInUser) {
                    return $deal->lead->lead_owner == $loggedInUser->id;
                });
            });
        }

        if ($request->ajax()) {
            $view = view('backend.deals.include.__pipeline_stages', compact('pipeline'))->render();
            return response()->json([
                'view' => $view,
                'stages' => $pipeline->stages,
            ]);
        }

        return view('backend.deals.index', compact('pipelinesbox', 'pipeline'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $pipelines = LeadPipeline::get();
        $leads = Lead::get();
        return view('backend.deals.create', compact('pipelines', 'leads'));
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
            'lead_id' => 'required|exists:leads,id',
            'lead_pipeline_id' => 'required|exists:lead_pipelines,id',
            'pipeline_stage_id' => 'required|exists:pipeline_stages,id',
            'close_date' => 'required|date',
            'value' => 'required|numeric',
        ]);

        $data['added_by'] = auth()->id();

        $deal = Deal::create($data);

        notify()->success(__('Deal created successfully.'));
        return redirect()->route('admin.deal.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $deal = Deal::with(['pipeline', 'pipelineStage', 'lead', 'notes'])->findOrFail($id);
        return view('backend.deals.show', compact('deal'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $deal = Deal::with(['pipeline', 'pipelineStage', 'lead'])->findOrFail($id);
        $pipelines = LeadPipeline::get();
        $stages = PipelineStage::where('lead_pipeline_id', $deal->lead_pipeline_id)->get();
        $leads = Lead::get();
        return view('backend.deals.edit', compact('deal', 'pipelines', 'stages', 'leads'));
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
            'lead_id' => 'required|exists:leads,id',
            'lead_pipeline_id' => 'required|exists:lead_pipelines,id',
            'pipeline_stage_id' => 'required|exists:pipeline_stages,id',
            'close_date' => 'required|date',
            'value' => 'required|numeric',
        ]);

        $deal = Deal::findOrFail($id);

        $deal->update($data);
        notify()->success(__('Deal updated successfully.'));
        return redirect()->route('admin.deal.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $deal = Deal::findOrFail($id);

        $deal->delete();
        notify()->success(__('Deal deleted successfully.'));
        return redirect()->route('admin.deal.index');
    }

    // Get Satges
    public function getStages($id)
    {
        $stages = PipelineStage::where('lead_pipeline_id', $id)->orderBy('priority')->get();

        return response()->json([
            'status' => 'success',
            'data' => $stages
        ]);
    }

    public function stageUpdate($id, Request $request)
    {
        $deal = Deal::find($id);
        $deal->pipeline_stage_id = $request->input('stage_id');
        $deal->save();

        return response()->json([
            'status' => 'success',
            'message' => __('Deal Stage Updated Successfully'),
        ]);
    }

}
