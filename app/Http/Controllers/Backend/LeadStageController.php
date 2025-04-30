<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\LeadPipeline;
use App\Models\PipelineStage;
use App\Services\LeadStageService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class LeadStageController extends Controller
{

    protected $leadStageService;

    public function __construct(LeadStageService $leadStageService)
    {
        $this->leadStageService = $leadStageService;
        $this->middleware('permission:lead-stage-list', ['only' => ['index']]);
        $this->middleware('permission:lead-stage-create', ['only' => ['store']]);
        $this->middleware('permission:lead-stage-edit', ['only' => ['update']]);
        $this->middleware('permission:lead-stage-delete', ['only' => ['destroy']]);
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
        $pipelines = LeadPipeline::all();
        return view('backend.setting.lead.stage.include.__create_form', compact('pipelines'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $rules = [
            'pipeline' => 'required',
            'name' => 'required|string|max:255',
            'label_color' => 'required|string|max:255',
        ];

        if ($request->pipeline) {
            $rules['name'] = [
                'required',
                'string',
                'max:255',
                Rule::unique('pipeline_stages', 'name')->where('lead_pipeline_id', $request->pipeline),
            ];
        }

        $data = $request->validate($rules);

        $stage = new PipelineStage();
        $stage->name = $data['name'];
        $stage->lead_pipeline_id = $data['pipeline'];
        $stage->label_color = $data['label_color'];

        $stage->slug = Str::slug($data['name']);

        $maxPriority = PipelineStage::where('lead_pipeline_id', $data['pipeline'])->max('priority') ?? 0;
        $stage->priority = $maxPriority + 1;
        $stage->save();

        notify()->success(__('Lead stage created successfully.'));
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
        $this->pipelines = LeadPipeline::all();
        $this->stage = PipelineStage::findOrFail($id);
        $this->stages = PipelineStage::where('lead_pipeline_id', $this->stage->lead_pipeline_id)
            ->orderBy('priority', 'asc')
            ->get();

        $this->lastStageColumn = $this->stages->filter(function ($value, $key) {
            return $value->priority == ($this->stage->priority - 1);
        })->first();

        $this->afterStageColumn = $this->stages->filter(function ($value, $key) {
            return $value->priority == ($this->stage->priority + 1);
        })->first();

        $this->maxPriority = PipelineStage::max('priority');

        $this->data = [
            'pipelines' => $this->pipelines,
            'stage' => $this->stage,
            'stages' => $this->stages,
            'lastStageColumn' => $this->lastStageColumn,
            'afterStageColumn' => $this->afterStageColumn,
            'maxPriority' => $this->maxPriority,
        ];

        return view('backend.setting.lead.stage.include.__edit_form', $this->data);
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
        $validate = $request->validate([
            'name' => 'required|unique:pipeline_stages,name,' . $request->route('stage') . ',id,lead_pipeline_id,' . $request->pipeline,
            'label_color' => 'required'
        ]);

        $stage = PipelineStage::findOrFail($id);
        $oldPosition = $stage->priority;
        $newPosition = $request->priority;

        if($request->has('before'))
        {
            PipelineStage::where('priority', '<', $oldPosition)
                ->where('priority', '>=', $newPosition)
                ->orderBy('priority', 'asc')
                ->increment('priority');

            $stage->priority = $request->priority;
        }
        elseif($oldPosition > $newPosition)
        {
            PipelineStage::where('priority', '<', $oldPosition)
                ->where('priority', '>', $newPosition)
                ->orderBy('priority', 'asc')
                ->increment('priority');

            $stage->priority = $request->priority + 1;
        }
        else
        {
            PipelineStage::where('priority', '>', $oldPosition)
                ->where('priority', '<=', $newPosition)
                ->orderBy('priority', 'asc')
                ->decrement('priority');

            $stage->priority = $request->priority;
        }

        $stage->name = $request->name;
        $stage->label_color = $request->label_color;
        $stage->save();

        notify()->success(__('Lead stage updated successfully.'));
        return redirect()->route('admin.lead.pipeline.index');
    }

    public function statusUpdate($id)
    {
        $stage = PipelineStage::find($id);
        $allPipelineStage = PipelineStage::select('id', 'default')->where('lead_pipeline_id', $stage->lead_pipeline_id)->get();

        foreach ($allPipelineStage as $leadStage) {
            if ($leadStage->id == $id) {
                $leadStage->default = '1';
            } else {
                $leadStage->default = '0';
            }

            $leadStage->save();
        }

        notify()->success(__('Lead stage status updated successfully.'));
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
        $board = PipelineStage::findOrFail($id);

        $otherColumns = PipelineStage::where('priority', '>', $board->priority)
            ->orderBy('priority', 'asc')
            ->get();

        foreach ($otherColumns as $column) {
            $pos = PipelineStage::where('priority', $column->priority)->first();
            $pos->priority = ($pos->priority - 1);
            $pos->save();
        }

        PipelineStage::destroy($id);

        notify()->success(__('Lead stage deleted successfully.'));
        return redirect()->route('admin.lead.pipeline.index');
    }
}
