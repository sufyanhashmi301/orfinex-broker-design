<?php

namespace App\Http\Controllers\Backend;

use App\Enums\FundedSchemeTypes;
use App\Enums\InterestPeriod;
use App\Enums\MultiLevelType;
use App\Http\Controllers\Controller;
use App\Models\ForexSchema;
use App\Models\ForexSchemaPhase;
use App\Models\ForexSchemaPhaseRule;
use App\Models\RebateRule;
use App\Models\Schedule;
use App\Models\MultiLevel;
use App\Models\SwapFreeAccount;
use App\Rules\MinDigits;
use App\Traits\ImageUpload;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class ForexSchemaController extends Controller
{
    use ImageUpload;

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function __construct()
    {
//        $this->middleware('permission:schema-list|schema-create|schema-edit', ['only' => ['index', 'store']]);
//        $this->middleware('permission:schema-create', ['only' => ['create', 'store']]);
//        $this->middleware('permission:schema-edit', ['only' => ['edit', 'update']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return Application|Factory|View
     */
    public function index()
    {

        $schemas = ForexSchema::orderBy('priority','asc')->paginate(10);

        return view('backend.forex_schema.index', compact('schemas'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Application|Factory|View
     */
    public function create()
    {
        return view('backend.forex_schema.create');
    }
    public function view($id)
    {
        $schema = ForexSchema::find($id);
        $swapBasedAccounts = MultiLevel::where('forex_scheme_id',$id)->where('type',MultiLevelType::SWAP)->orderBy('level_order','asc')->get();
        $swapFreeAccounts = MultiLevel::where('forex_scheme_id',$id)->where('type',MultiLevelType::SWAP_FREE)->orderBy('level_order','asc')->get();
        $rebateRules = RebateRule::where('status',true)->orderBy('title','asc')->get();

        return view('backend.multi_level.index',compact('schema','swapBasedAccounts','swapFreeAccounts','rebateRules'));
    }
    /**
     * Store a newly created resource in storage.
     *
     * @return RedirectResponse
     */
    public function store(Request $request)
    {

//        dd($request->all());
        $validator = Validator::make($request->all(), [
            'title' => 'required',
            'leverage' => 'required',
//            'commission' => 'required',
//            'spread' => 'required',
            'is_scalable' => 'required',
            'is_weekend_holding' => 'required',
            'is_refundable' => 'required',
            'account_limit' => 'required|integer|min:1|max:50',
            'priority' => 'required|integer',
            'start_range' => array_merge(setting('is_forex_group_range', 'global') ? ['required', new MinDigits(6)] : ['nullable', new MinDigits(6)], ['integer']),
            'end_range' => array_merge(setting('is_forex_group_range', 'global') ? ['required', new MinDigits(6)] : ['nullable', new MinDigits(6)], ['integer']),
            'group' => 'required|max:500',
            'type' => [
                'required',
                Rule::in([
                    FundedSchemeTypes::CHALLENGE_PHASE,
                    FundedSchemeTypes::FUNDED_PHASE,
                    FundedSchemeTypes::DIRECT_FUNDING,
                ])
            ],
            'validity_count' => 'required|integer|min:1|max:12',
            'rules' => 'required|array|min:1', // Ensuring at least one rule is set
            'rules.*.allotted_funds' => 'required|numeric',
            'rules.*.daily_drawdown_limit' => 'required|numeric',
            'rules.*.max_drawdown_limit' => 'required|numeric',
            'rules.*.profit_target' => 'required|numeric',
            'rules.*.fee' => 'required|numeric',
            'rules.*.discount' => 'required|numeric',

        ]);

        if ($validator->fails()) {
            notify()->error($validator->errors()->first(), 'Error');

            return redirect()->back()->withErrors($validator)->withInput();
        }

        $input = $request->all();
//        dd($input);

        $finalData = [
            'title' => $input['title'],
            'badge' => $input['badge'],
            'commission' => $input['commission'],
            'spread' => $input['spread'],
            'leverage' => $input['leverage'],
//            'first_min_deposit' => $input['first_min_deposit'],
            'account_limit' => $input['account_limit'],
            'desc' => $input['desc'],
            'country' => isset($input['country']) ? json_encode($input['country']) : json_encode(['All']),
            'tags' => isset($input['tags']) ? json_encode($input['tags']) : null,
            'is_weekend_holding' => $input['is_weekend_holding'],
            'is_scalable' => $input['is_scalable'],
            'is_refundable' => $input['is_refundable'],
            'status' => $input['status'],
            'priority' => $input['priority'],
            'upto_allotted_fund' => $input['upto_allotted_fund'],
            'upto_profit_target' => $input['upto_profit_target'],
            'upto_daily_max_loss' => $input['upto_daily_max_loss'],
            'upto_maximum_loss' => $input['upto_maximum_loss'],
            'start_range' => !empty($input['start_range']) ? $input['start_range'] : null,
            'end_range' => !empty($input['end_range']) ? $input['end_range'] : null,
            'icon' => isset($input['icon']) ? self::imageUploadTrait($input['icon']) : null,

        ];
        $schema = ForexSchema::create($finalData);


        $phaseData = [
            'forex_schema_id' => $schema->id,
            'group' => $input['group'],
            'type' => $input['type'],
            'validity_count' => $input['validity_count'],
            'term_type' => InterestPeriod::MONTHLY,
            'server' => $input['server']
        ];
        $phase = ForexSchemaPhase::create($phaseData);
        // Save rules
        foreach ($request->rules as $rule) {
            do {
                $unique_id = 'R-'.mt_rand(10000, 99999); // Generates a random number between 10000 and 99999
            } while (ForexSchemaPhaseRule::where('unique_id', $unique_id)->exists());

            ForexSchemaPhaseRule::create([
                'forex_schema_phase_id' => $phase->id,
                'unique_id' => $unique_id,
                'allotted_funds' => $rule['allotted_funds'],
                'daily_drawdown_limit' => $rule['daily_drawdown_limit'],
                'max_drawdown_limit' => $rule['max_drawdown_limit'],
                'profit_target' => $rule['profit_target'],
                'amount' => $rule['fee'],
                'total' => $rule['fee'],
                'discount' => $rule['discount'],
                'is_new_order' => $rule['is_new_order'] ?? 0,
            ]);
        }
        notify()->success('schema created successfully');
        return redirect()->route('admin.accountType.index');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return Application|Factory|View
     */
    public function edit($id)
    {
        $schema = ForexSchema::with(['forexSchemaPhases.forexSchemaPhaseRules'])->findOrFail($id);
//        dd($schema);
        return view('backend.forex_schema.edit', compact('schema'));
    }


    /**
     * Update the specified resource in storage.
     *
     * @param int $id
     * @return RedirectResponse
     */
    public function update(Request $request, $id)
    {
        // Define validation rules
        $validator = Validator::make($request->all(), [
            'title' => 'required',
            'leverage' => 'required',
//            'commission' => 'required',
//            'spread' => 'required',
            'is_scalable' => 'required|boolean',
            'is_weekend_holding' => 'required|boolean',
            'is_refundable' => 'required|boolean',
            'account_limit' => 'required|integer|min:1|max:50',
            'priority' => 'required|integer',
            'start_range' => array_merge(setting('is_forex_group_range', 'global') ? ['required', new MinDigits(6)] : ['nullable', new MinDigits(6)], ['integer']),
            'end_range' => array_merge(setting('is_forex_group_range', 'global') ? ['required', new MinDigits(6)] : ['nullable', new MinDigits(6)], ['integer']),
            'phases.*.group' => 'required|string',
            'phases.*.type' => 'required|in:' . implode(',', [\App\Enums\FundedSchemeTypes::CHALLENGE_PHASE, \App\Enums\FundedSchemeTypes::FUNDED_PHASE, \App\Enums\FundedSchemeTypes::DIRECT_FUNDING]),
            'phases.*.validity_count' => 'required|integer|min:1|max:12',
            'phases.*.server' => 'required|string',
            'rules.*.*.allotted_funds' => 'required|numeric|min:0',
            'rules.*.*.daily_drawdown_limit' => 'required|numeric|min:0',
            'rules.*.*.max_drawdown_limit' => 'required|numeric|min:0',
            'rules.*.*.profit_target' => 'required|numeric|min:0',
            'rules.*.*.fee' => 'required|numeric|min:0',
            'rules.*.*.discount' => 'required|numeric|min:0',
            'rules.*.*.is_new_order' => 'nullable|boolean',
        ]);

        // Return validation errors, if any
        if ($validator->fails()) {
            notify()->error($validator->errors()->first(), 'Error');
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // Find the existing schema record
        $schema = ForexSchema::findOrFail($id);

        // Prepare the schema data for updating
        $input = $request->all();
        $finalData = [
            'title' => $input['title'],
            'badge' => $input['badge'],
            'spread' => $input['spread'],
            'commission' => $input['commission'],
            'leverage' => $input['leverage'],
//            'first_min_deposit' => !empty($input['first_min_deposit']) ? $input['first_min_deposit'] : null,
            'account_limit' => $input['account_limit'],
            'desc' => $input['desc'],
            'country' => isset($input['country']) ? json_encode($input['country']) : json_encode(['All']),
            'tags' => isset($input['tags']) ? json_encode($input['tags']) : null,
            'is_weekend_holding' => $input['is_weekend_holding'],
            'is_scalable' => $input['is_scalable'],
            'is_refundable' => $input['is_refundable'],
            'status' => $input['status'],
            'priority' => $input['priority'],
            'start_range' => !empty($input['start_range']) ? $input['start_range'] : null,
            'end_range' => !empty($input['end_range']) ? $input['end_range'] : null,
            'icon' => $request->hasFile('icon') ? self::imageUploadTrait($input['icon']) : $schema->icon,
        ];

        // Update the schema
        $schema->update($finalData);

        // Get current phase and rule IDs to track deletions
        $existingPhaseIds = $schema->forexSchemaPhases->pluck('id')->toArray();
        $submittedPhaseIds = array_column($input['phases'], 'id');
        $phasesToDelete = array_diff($existingPhaseIds, $submittedPhaseIds);

        // Delete phases that are not submitted
        ForexSchemaPhase::destroy($phasesToDelete);

        // Handle updating/creating phases and rules
        foreach ($input['phases'] as $phaseIndex => $phaseData) {
            // Update or create the phase
            $phase = $schema->forexSchemaPhases()->updateOrCreate(
                ['id' => $input['phases'][$phaseIndex]['id'] ?? null],  // Update if existing, create if new
                [
                    'group' => $phaseData['group'],
                    'type' => $phaseData['type'],
                    'validity_count' => $phaseData['validity_count'],
                    'server' => $phaseData['server'],
                ]
            );

            // Get current rule IDs for this phase to track deletions
            $existingRuleIds = $phase->forexSchemaPhaseRules->pluck('id')->toArray();
            $submittedRuleIds = array_column($input['rules'][$phaseIndex] ?? [], 'id');
            $rulesToDelete = array_diff($existingRuleIds, $submittedRuleIds);

            // Delete rules that are not submitted
            ForexSchemaPhaseRule::destroy($rulesToDelete);

            // Handle updating/creating rules for the phase
            if (isset($input['rules'][$phaseIndex])) {
                foreach ($input['rules'][$phaseIndex] as $ruleIndex => $ruleData) {
                    // If a new rule is being created (no `id`), generate a unique_id
                    if (empty($ruleData['id'])) {
                        do {
                            $unique_id = 'R-' . mt_rand(10000, 99999);
                        } while (ForexSchemaPhaseRule::where('unique_id', $unique_id)->exists());
                    }

                    $phase->forexSchemaPhaseRules()->updateOrCreate(
                        ['id' => $ruleData['id'] ?? null],  // Update if existing, create if new
                        [
                            'unique_id' => $ruleData['id'] ? $ruleData['unique_id'] : $unique_id,
                            'allotted_funds' => $ruleData['allotted_funds'],
                            'daily_drawdown_limit' => $ruleData['daily_drawdown_limit'],
                            'max_drawdown_limit' => $ruleData['max_drawdown_limit'],
                            'profit_target' => $ruleData['profit_target'],
                            'amount' => $ruleData['fee'],
                            'total' => $ruleData['fee'],
                            'discount' => $ruleData['discount'],
                            'is_new_order' => isset($ruleData['is_new_order']) ? $ruleData['is_new_order'] : 0,
                        ]
                    );
                }
            }
        }

        // Success notification
        notify()->success('Schema updated successfully.');

        // Redirect back to the schema listing page
        return redirect()->route('admin.accountType.index');
    }

//    public function update(Request $request, $id)
//    {
//
//        $validator = Validator::make($request->all(), [
//            'title' => 'required',
//            'leverage' => 'required',
//            'commission' => 'required',
//            'spread' => 'required',
//            'is_scalable' => 'required',
//            'is_weekend_holding' => 'required',
//            'is_refundable' => 'required',
//            'account_limit' => 'required|integer|min:1|max:50',
//            'priority' => 'required|integer',
//            'start_range' => array_merge(setting('is_forex_group_range', 'global') ? ['required', new MinDigits(6)] : ['nullable', new MinDigits(6)], ['integer']),
//            'end_range' => array_merge(setting('is_forex_group_range', 'global') ? ['required', new MinDigits(6)] : ['nullable', new MinDigits(6)], ['integer']),
//
//        ]);
//
//        if ($validator->fails()) {
//            notify()->error($validator->errors()->first(), 'Error');
//
//            return redirect()->back();
//        }
////        dd($request->all());
//        $schema = ForexSchema::find($id);
//        $input = $request->all();
//        $finalData = [
//            'title' => $input['title'],
//            'badge' => $input['badge'],
//            'spread' => $input['spread'],
//            'commission' => $input['commission'],
//            'leverage' => $input['leverage'],
//            'first_min_deposit' => !empty($input['first_min_deposit']) ? $input['first_min_deposit'] : null,
//            'account_limit' => $input['account_limit'],
//            'desc' => $input['desc'],
//            'country' => isset($input['country']) ? json_encode($input['country']) : json_encode(['All']),
//            'tags' => isset($input['tags']) ? json_encode($input['tags']) : null,
//            'is_weekend_holding' => $input['is_weekend_holding'],
//            'is_scalable' => $input['is_scalable'],
//            'is_refundable' => $input['is_refundable'],
//            'status' => $input['status'],
//            'priority' => $input['priority'],
//            'start_range' => !empty($input['start_range']) ? $input['start_range'] : null,
//            'end_range' => !empty($input['end_range']) ? $input['end_range'] : null,
//            'icon' => $request->hasFile('icon') ? self::imageUploadTrait($input['icon']) : $schema->icon,
//        ];
//
//        $schema->update($finalData);
//
//        notify()->success('schema Update successfully');
//
//        return redirect()->route('admin.accountType.index');
//    }

    public function destroy($id)
    {
        $schema = ForexSchema::findOrFail($id);
        if ($schema->forexAccounts()->exists()) {
            notify()->error('Cannot delete schema because there are accounts associated with it.');
            return redirect()->route('admin.accountType.index');
        }
        $schema->delete();

        notify()->success('schema deleted successfully');

        return redirect()->route('admin.accountType.index');
    }
}
