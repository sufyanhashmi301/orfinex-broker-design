<?php

namespace App\Http\Controllers\Backend;

use App\Enums\MultiLevelType;
use App\Http\Controllers\Controller;
use App\Models\ForexSchema;
use App\Models\RebateRule;
use App\Models\Schedule;
use App\Models\MultiLevel;
use App\Models\IBGroup;
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

        $schemas = ForexSchema::orderBy('priority','asc')->traderType()->paginate(10);

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

    public function manageLevel()
    {
        $schemas = ForexSchema::orderBy('priority', 'asc')->traderType()->get();
        return view('backend.multi_level.manage_level', compact('schemas'));
    }

    public function view($id)
    {
        $schema = ForexSchema::find($id);
        $swapBasedAccounts = MultiLevel::where('forex_scheme_id',$id)->where('type',MultiLevelType::SWAP)->orderBy('level_order','asc')->get();
        $swapFreeAccounts = MultiLevel::where('forex_scheme_id',$id)->where('type',MultiLevelType::SWAP_FREE)->orderBy('level_order','asc')->get();
        $rebateRules = RebateRule::where('status',true)->orderBy('title','asc')->get();
        $ibGroups = IBGroup::where('status', 1)->orderBy('name', 'asc')->get();
        return view('backend.multi_level.index',compact('schema','swapBasedAccounts','swapFreeAccounts','rebateRules', 'ibGroups'));
    }
    /**
     * Store a newly created resource in storage.
     *
     * @return RedirectResponse
     */
    public function store(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'trader_type' => 'required',
            'title' => 'required',
            'leverage' => 'required',
            'real_swap_free' => 'required_without_all:real_islamic,demo_swap_free,demo_islamic',
            'real_islamic' => 'required_without_all:real_swap_free,demo_swap_free,demo_islamic',
            'demo_swap_free' => 'required_without_all:real_swap_free,real_islamic,demo_islamic',
            'demo_islamic' => 'required_without_all:real_swap_free,real_islamic,demo_swap_free',
            'commission' => 'required',
            'spread' => 'required',
            'is_withdraw' => 'required',
            'is_internal_transfer' => 'required',
            'is_external_transfer' => 'required',
            'account_limit' => 'required|integer|min:1|max:50',
            'min_amount' => 'nullable|integer|min:0|max:500000',
            'priority' => 'required|integer',
            'start_range' => array_merge(setting('is_forex_group_range', 'global') ? ['required', new MinDigits(6)] : ['nullable', new MinDigits(6)], ['integer']),
            'end_range' => array_merge(setting('is_forex_group_range', 'global') ? ['required', new MinDigits(6)] : ['nullable', new MinDigits(6)], ['integer']),
        ], [
            'real_swap_free.required_without_all' => 'At least one field must be filled of groups.',
            'real_islamic.required_without_all' => 'At least one field must be filled of groups.',
            'demo_swap_free.required_without_all' => 'At least one field must be filled of groups.',
            'demo_islamic.required_without_all' => 'At least one field must be filled of groups.',
        ]);

        if ($validator->fails()) {
            notify()->error($validator->errors()->first(), 'Error');

            return redirect()->back();
        }

        $input = $request->all();

        $finalData = [
            'trader_type' => $input['trader_type'],
            'title' => $input['title'],
            'badge' => $input['badge'],
            'commission' => $input['commission'],
            'spread' => $input['spread'],
            'leverage' => $input['leverage'],
            'first_min_deposit' => !empty($input['first_min_deposit']) ? $input['first_min_deposit'] : null,
            'min_amount' => !empty($input['min_amount']) ? $input['min_amount'] : 0,
            'account_limit' => $input['account_limit'],
            'real_swap_free' => $input['real_swap_free'],
            'is_real_islamic' => isset($input['is_real_islamic']) ? $input['is_real_islamic'] : 0,
            'real_islamic' => $input['real_islamic'],
            'is_demo_islamic' => isset($input['is_demo_islamic']) ? $input['is_demo_islamic'] : 0,
            'demo_swap_free' => $input['demo_swap_free'],
            'demo_islamic' => $input['demo_islamic'],
            'desc' => $input['desc'],
            'country' => isset($input['country']) ? json_encode($input['country']) : json_encode(['All']),
            'tags' => isset($input['tags']) ? json_encode($input['tags']) : null,
            'is_withdraw' => $input['is_withdraw'],
            'is_internal_transfer' => $input['is_internal_transfer'],
            'is_external_transfer' => $input['is_external_transfer'],
            'status' => $input['status'],
            'priority' => $input['priority'],
            'start_range' => !empty($input['start_range']) ? $input['start_range'] : null,
            'end_range' => !empty($input['end_range']) ? $input['end_range'] : null,
            'icon' => isset($input['icon']) ? self::imageUploadTrait($input['icon']) : null,
        ];
//        dd($finalData);
        ForexSchema::create($finalData);
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
        $schema = ForexSchema::find($id);
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

        $validator = Validator::make($request->all(), [
            'title' => 'required',
            'real_swap_free' => 'required_without_all:real_islamic,demo_swap_free,demo_islamic',
            'real_islamic' => 'required_without_all:real_swap_free,demo_swap_free,demo_islamic',
            'demo_swap_free' => 'required_without_all:real_swap_free,real_islamic,demo_islamic',
            'demo_islamic' => 'required_without_all:real_swap_free,real_islamic,demo_swap_free',
            'leverage' => 'required',
            'commission' => 'required',
            'spread' => 'required',
            'is_withdraw' => 'required',
            'is_internal_transfer' => 'required',
            'is_external_transfer' => 'required',
            'account_limit' => 'required|integer|min:1|max:50',
            'min_amount' => 'required|integer|min:0|max:500000',
            'priority' => 'required|integer',
            'start_range' => array_merge(setting('is_forex_group_range', 'global') ? ['required', new MinDigits(6)] : ['nullable', new MinDigits(6)], ['integer']),
            'end_range' => array_merge(setting('is_forex_group_range', 'global') ? ['required', new MinDigits(6)] : ['nullable', new MinDigits(6)], ['integer']),

        ], [
            'real_swap_free.required_without_all' => 'At least one field must be filled of groups.',
            'real_islamic.required_without_all' => 'At least one field must be filled of groups.',
            'demo_swap_free.required_without_all' => 'At least one field must be filled of groups.',
            'demo_islamic.required_without_all' => 'At least one field must be filled of groups.',
        ]);

        if ($validator->fails()) {
            notify()->error($validator->errors()->first(), 'Error');

            return redirect()->back();
        }
//        dd($request->all());
        $schema = ForexSchema::find($id);
        $input = $request->all();
        $finalData = [
            'title' => $input['title'],
            'badge' => $input['badge'],
            'spread' => $input['spread'],
            'commission' => $input['commission'],
            'leverage' => $input['leverage'],
            'first_min_deposit' => !empty($input['first_min_deposit']) ? $input['first_min_deposit'] : null,
            'min_amount' => !empty($input['min_amount']) ? $input['min_amount'] : 0,
            'account_limit' => $input['account_limit'],
            'real_swap_free' => $input['real_swap_free'],
            'is_real_islamic' => isset($input['is_real_islamic']) ? $input['is_real_islamic'] : 0,
            'real_islamic' => $input['real_islamic'],
            'is_demo_islamic' => isset($input['is_demo_islamic']) ? $input['is_demo_islamic'] : 0,
            'demo_swap_free' => $input['demo_swap_free'],
            'demo_islamic' => $input['demo_islamic'],
            'desc' => $input['desc'],
            'country' => isset($input['country']) ? json_encode($input['country']) : json_encode(['All']),
            'tags' => isset($input['tags']) ? json_encode($input['tags']) : null,
            'is_withdraw' => $input['is_withdraw'],
            'is_internal_transfer' => $input['is_internal_transfer'],
            'is_external_transfer' => $input['is_external_transfer'],
            'status' => $input['status'],
            'priority' => $input['priority'],
            'start_range' => !empty($input['start_range']) ? $input['start_range'] : null,
            'end_range' => !empty($input['end_range']) ? $input['end_range'] : null,
            'icon' => $request->hasFile('icon') ? self::imageUploadTrait($input['icon']) : $schema->icon,
        ];

        $schema->update($finalData);

        notify()->success('schema Update successfully');

        return redirect()->route('admin.accountType.index');
    }
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
