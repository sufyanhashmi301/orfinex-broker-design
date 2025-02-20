<?php

namespace App\Http\Controllers\Backend;

use App\Helper\Reply;
use App\Http\Controllers\Controller;
use Illuminate\Support\Str;
use App\Models\LeadPipeline;
use App\Models\PipelineStage;
use Illuminate\Http\Request;

class LeadPipelineController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $pipelines = LeadPipeline::with('stages')->get();
        return view('backend.setting.lead.pipeline.index', compact('pipelines'));
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
        $validated = $request->validate([
            'name' => 'required|unique:lead_pipelines,name',
            'label_color' => 'required'
        ]);

        $pipeline = new LeadPipeline();

        $pipeline->name = $request->name;
        $pipeline->label_color = $request->label_color;
        $pipeline->added_by = auth()->user()->id;

        $pipeline->slug = Str::slug($pipeline->name, '-');
        $pipeline->save();

        $pipelineStages = [
            ['name' => 'Generated', 'slug' => 'generated', 'lead_pipeline_id' => $pipeline->id, 'priority' => 1, 'default' => 1, 'label_color' => '#FFE700'],
            ['name' => 'On going', 'slug' => 'on-going', 'lead_pipeline_id' => $pipeline->id, 'priority' => 2, 'default' => 0, 'label_color' => '#009EFF'],
            ['name' => 'Win', 'slug' => 'win', 'lead_pipeline_id' => $pipeline->id, 'priority' => 3, 'default' => 0, 'label_color' => '#1FAE07'],
            ['name' => 'Lost', 'slug' => 'lost', 'lead_pipeline_id' => $pipeline->id, 'priority' => 4, 'default' => 0, 'label_color' => '#DB1313']
        ];

        PipelineStage::insert($pipelineStages);

        notify()->success(__('Pipeline created successfully.'));
        return redirect()->route('admin.lead.pipeline.index');

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
        $pipeline = LeadPipeline::findOrFail($id);
        return view('backend.setting.lead.pipeline.include.__edit_pipeline_form', compact('pipeline'));
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
        $validated = $request->validate([
            'name' => 'required|unique:lead_pipelines,name',
            'label_color' => 'required'
        ]);

        $pipeline = LeadPipeline::findOrFail($id);
        $pipeline->name = $request->name;
        $pipeline->label_color = $request->label_color;
        $pipeline->save();

        notify()->success(__('Pipeline updated successfully.'));
        return back();
    }

    public function statusUpdate($id)
    {
        $allLeadSPipelines = LeadPipeline::select('id', 'default')->get();

        foreach($allLeadSPipelines as $pipeline){
            if($pipeline->id == $id){
                $pipeline->default = '1';
            }
            else{
                $pipeline->default = '0';
            }

            $pipeline->save();
        }

        notify()->success(__('Pipeline status updated successfully.'));
        return response()->json(['status' => 'success']);
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
