<?php

namespace App\Http\Controllers\Backend;

use App\Enums\MultiLevelType;
use App\Http\Controllers\Controller;
use App\Models\Branch;
use App\Models\ForexSchema;
use App\Models\RebateRule;
use App\Models\Schedule;
use App\Models\MultiLevel;
use App\Models\IbGroup;
use App\Models\SwapFreeAccount;
use App\Models\PlatformGroup;
use App\Models\AccountTypeCategory;
use App\Rules\MinDigits;
use App\Traits\ImageUpload;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\AccountTypesExport;

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
       $this->middleware('permission:account-type-list|account-type-create|account-type-edit', ['only' => ['index', 'store']]);
       $this->middleware('permission:account-type-create', ['only' => ['create', 'store']]);
       $this->middleware('permission:account-type-edit', ['only' => ['edit', 'update']]);
       $this->middleware('permission:account-type-delete', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return Application|Factory|View
     */
    public function index(Request $request)
    {
        $schemas = ForexSchema::with(['accountCategories', 'rebateRules', 'branches'])
            ->orderBy('priority', 'asc')
            ->traderType();
    
        // Apply filters
        if ($request->filled('title')) {
            $schemas->where('title', 'like', '%' . $request->input('title') . '%');
        }
        if ($request->filled('trader_type')) {
            $schemas->where('trader_type', 'like', '%' . $request->input('trader_type') . '%');
        }
        if ($request->filled('leverage')) {
            $schemas->where('leverage', 'like', '%' . $request->input('leverage') . '%');
        }
        if ($request->filled('badge')) {
            $schemas->where('badge', 'like', '%' . $request->input('badge') . '%');
        }
        if ($request->filled('status') && in_array($request->input('status'), ['0', '1'])) {
            $schemas->where('status', $request->input('status'));
        }
    
        // Force page 1 when filters change
        if ($request->hasAny(['title', 'trader_type', 'leverage', 'badge', 'status']) && !$request->ajax()) {
            $request->merge(['page' => 1]);
        }
    
        $schemas = $schemas->paginate(10);
    
        if ($request->ajax()) {
            return response()->json([
                'html' => view('backend.forex_schema.index', compact('schemas'))->render(),
                'current_page' => $schemas->currentPage(),
                'filters_active' => $request->anyFilled(['title', 'trader_type', 'leverage', 'badge', 'status'])
            ]);
        }
    
        return view('backend.forex_schema.index', compact('schemas'));
    }
    
    public function export(Request $request)
    {
        $schemas = ForexSchema::with(['accountCategories', 'rebateRules'])
            ->orderBy('priority', 'asc')
            ->traderType();
    
        // Apply the same filters as index method
        if ($request->filled('title')) {
            $schemas->where('title', 'like', '%' . $request->input('title') . '%');
        }
        if ($request->filled('trader_type')) {
            $schemas->where('trader_type', 'like', '%' . $request->input('trader_type') . '%');
        }
        if ($request->filled('leverage')) {
            $schemas->where('leverage', 'like', '%' . $request->input('leverage') . '%');
        }
        if ($request->filled('badge')) {
            $schemas->where('badge', 'like', '%' . $request->input('badge') . '%');
        }
        if ($request->filled('status') && in_array($request->input('status'), ['0', '1'])) {
            $schemas->where('status', $request->input('status'));
        }
    
        // Get the filtered schemas
        $schemas = $schemas->get();
    
        // Use the export class
        return Excel::download(new AccountTypesExport($schemas), 'account_types_' . date('Y-m-d') . '.xlsx');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Application|Factory|View
     */
    public function create()
    {
        $rebateRules = RebateRule::where('status', true)->orderBy('title', 'asc')->get();
        $accountTypeCategories = AccountTypeCategory::where('status', true)->get();
        $branches = Branch::where('status', 1)->get();
        return view('backend.forex_schema.create', compact('accountTypeCategories', 'rebateRules', 'branches'));
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
        $ibGroups = IbGroup::where('status', 1)->orderBy('name', 'asc')->get();
        $platformGroups = PlatformGroup::where('status', 1)->where('risk_book_id', 0)->get();
        return view('backend.multi_level.index',compact('schema','swapBasedAccounts','swapFreeAccounts','rebateRules', 'ibGroups', 'platformGroups'));
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
            'is_cent_account' => 'required',
            'account_limit' => 'required|integer|min:1|max:50',
            'min_amount' => 'nullable|integer|min:0|max:500000',
            'priority' => 'required|integer',
            'is_update_trading_password' => 'required|boolean',
            'is_update_investor_password' => 'required|boolean',
            'demo_deposit_amount' => 'nullable|numeric|min:0|max:50000000',
            'start_range' => array_merge(setting('is_forex_group_range', 'global') ? ['required', new MinDigits(5)] : ['nullable', new MinDigits(5)], ['integer']),
            'end_range' => array_merge(setting('is_forex_group_range', 'global') ? ['required', new MinDigits(5)] : ['nullable', new MinDigits(5)], ['integer']),
        ], [
            'real_swap_free.required_without_all' => 'At least one field must be filled of groups.',
            'real_islamic.required_without_all' => 'At least one field must be filled of groups.',
            'demo_swap_free.required_without_all' => 'At least one field must be filled of groups.',
            'demo_islamic.required_without_all' => 'At least one field must be filled of groups.',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator->errors())->withInput();
        }

        $input = $request->all();
        $input['demo_deposit_amount'] = $input['demo_deposit_amount'] === '' ? null : $input['demo_deposit_amount'];
        $input['desc'] = str_replace(['{', '}'], ['<', '>'], $request->desc);

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
            'demo_deposit_amount' => $input['demo_deposit_amount'],
            'account_category_id' => $input['account_category_id'],
            'country' => isset($input['country']) ? json_encode($input['country']) : null,
            'tags' => isset($input['tags']) ? json_encode($input['tags']) : null,
            'is_withdraw' => $input['is_withdraw'],
            'is_internal_transfer' => $input['is_internal_transfer'],
            'is_external_transfer' => $input['is_external_transfer'],
            'is_cent_account' => $input['is_cent_account'],
            'status' => $input['status'],
            'priority' => $input['priority'],
            'start_range' => !empty($input['start_range']) ? $input['start_range'] : null,
            'end_range' => !empty($input['end_range']) ? $input['end_range'] : null,
            'icon' => isset($input['icon']) ? self::imageUploadTrait($input['icon']) : null,
            'is_global' => $input['is_global'],
            'is_update_trading_password' => $input['is_update_trading_password'] ?? 0,
            'is_update_investor_password' => $input['is_update_investor_password'] ?? 0,

        ];
//        dd($finalData);
        $schema = ForexSchema::create($finalData);

        // Attach rebate rules if provided
        if (!empty($input['rebate_rules'])) {
            $schema->rebateRules()->sync($input['rebate_rules']);
        }

        // Attach branches if provided
        if (!empty($input['branches'])) {
            $schema->branches()->sync($input['branches']);
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
        $schema = ForexSchema::with('branches')->find($id);
        $rebateRules = RebateRule::where('status', true)->orderBy('title', 'asc')->get();
        $attachedRebateRules = $schema->rebateRules->pluck('id')->toArray();
        $accountTypeCategories = AccountTypeCategory::where('status', true)->get();
        $branches = Branch::where('status', 1)->get();
        $attachedBranches = $schema->branches->pluck('id')->toArray();
        return view('backend.forex_schema.edit', compact('schema', 'accountTypeCategories', 'rebateRules', 'attachedRebateRules', 'branches', 'attachedBranches'));
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
            'is_update_trading_password' => 'required|boolean',
            'is_update_investor_password' => 'required|boolean',
            'demo_deposit_amount' => 'nullable|numeric|min:0',
            'start_range' => array_merge(setting('is_forex_group_range', 'global') ? ['required', new MinDigits(5)] : ['nullable', new MinDigits(5)], ['integer']),
            'end_range' => array_merge(setting('is_forex_group_range', 'global') ? ['required', new MinDigits(5)] : ['nullable', new MinDigits(5)], ['integer']),

        ], [
            'real_swap_free.required_without_all' => 'At least one field must be filled of groups.',
            'real_islamic.required_without_all' => 'At least one field must be filled of groups.',
            'demo_swap_free.required_without_all' => 'At least one field must be filled of groups.',
            'demo_islamic.required_without_all' => 'At least one field must be filled of groups.',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator->errors())->withInput();
        }
//        dd($request->all());
        $schema = ForexSchema::find($id);
        $input = $request->all();
        $input['demo_deposit_amount'] = $input['demo_deposit_amount'] === '' ? null : $input['demo_deposit_amount'];

        $input['desc'] = str_replace(['{', '}'], ['<', '>'], $request->desc);

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
            'demo_deposit_amount' => $input['demo_deposit_amount'],
            'account_category_id' => $input['account_category_id'],
            'country' => isset($input['country']) ? json_encode($input['country']) : null,
            'tags' => isset($input['tags']) ? json_encode($input['tags']) : null,
            'is_withdraw' => $input['is_withdraw'],
            'is_internal_transfer' => $input['is_internal_transfer'],
            'is_external_transfer' => $input['is_external_transfer'],
            'is_cent_account' => $input['is_cent_account'],
            'status' => $input['status'],
            'priority' => $input['priority'],
            'start_range' => !empty($input['start_range']) ? $input['start_range'] : null,
            'end_range' => !empty($input['end_range']) ? $input['end_range'] : null,
            'icon' => $request->hasFile('icon') ? self::imageUploadTrait($input['icon']) : $schema->icon,
            'is_global' => $input['is_global'],
            'is_update_trading_password' => $input['is_update_trading_password'] ?? 0,
            'is_update_investor_password' => $input['is_update_investor_password'] ?? 0,
        ];

        $schema->update($finalData);

        $schema->rebateRules()->sync($input['rebate_rules'] ?? []);

        // Sync branches
        $schema->branches()->sync($input['branches'] ?? []);

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
    public static function accountTypeSetting()
    {
        return view('backend.forex_schema.account_type_settings');
    }
}
