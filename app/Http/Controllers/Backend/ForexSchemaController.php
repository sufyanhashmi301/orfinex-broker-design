<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\ForexSchema;
use App\Models\Schedule;
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
        
        $schemas = ForexSchema::orderBy('priority','asc')->get();

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

    /**
     * Store a newly created resource in storage.
     *
     * @return RedirectResponse
     */
    public function store(Request $request)
    {
//        dd($request->all());

        $validator = Validator::make($request->all(), [
            'icon' => 'required',
            'title' => 'required',
            'leverage' => 'required',
            'commission' => 'required',
            'spread' => 'required',
            'is_withdraw' => 'required',
            'is_ib_partner' => 'required',
            'is_internal_transfer' => 'required',
            'is_external_transfer' => 'required',
            'priority' => 'required|integer|unique:forex_schemas,priority',
        ]);

        if ($validator->fails()) {
            notify()->error($validator->errors()->first(), 'Error');

            return redirect()->back();
        }

        $input = $request->all();

        $finalData = [
            'title' => $input['title'],
            'badge' => $input['badge'],
            'commission' => $input['commission'],
            'spread' => $input['spread'],
            'leverage' => $input['leverage'],
            'first_min_deposit' => $input['first_min_deposit'],
            'real_swap_free' => $input['real_swap_free'],
            'real_islamic' => $input['real_islamic'],
            'demo_swap_free' => $input['demo_swap_free'],
            'demo_islamic' => $input['demo_islamic'],
            'desc' => $input['desc'],
            'country' => isset($input['country']) ? json_encode($input['country']) : json_encode(['All']),
            'is_withdraw' => $input['is_withdraw'],
            'is_ib_partner' => $input['is_ib_partner'],
            'is_internal_transfer' => $input['is_internal_transfer'],
            'is_external_transfer' => $input['is_external_transfer'],
            'is_bonus' => $input['is_bonus'],
            'status' => $input['status'],
            'priority' => $input['priority'],
            'icon' => self::imageUploadTrait($input['icon']),
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
            'leverage' => 'required',
            'spread' => 'required',
            'commission' => 'required',
            'is_withdraw' => 'required',
            'is_ib_partner' => 'required',
            'is_internal_transfer' => 'required',
            'is_external_transfer' => 'required',
            'priority' => 'required|integer|unique:forex_schemas,priority,' . $id,

        ]);

        if ($validator->fails()) {
            notify()->error($validator->errors()->first(), 'Error');

            return redirect()->back();
        }

        $schema = ForexSchema::find($id);
        $input = $request->all();
//dd($input);
        $finalData = [
            'title' => $input['title'],
            'badge' => $input['badge'],
            'spread' => $input['spread'],
            'commission' => $input['commission'],
            'leverage' => $input['leverage'],
            'first_min_deposit' => !empty($input['first_min_deposit']) ? $input['first_min_deposit'] : null,
            'real_swap_free' => $input['real_swap_free'],
            'real_islamic' => $input['real_islamic'],
            'demo_swap_free' => $input['demo_swap_free'],
            'demo_islamic' => $input['demo_islamic'],
            'desc' => $input['desc'],
            'country' => isset($input['country']) ? json_encode($input['country']) : json_encode(['All']),
            'is_withdraw' => $input['is_withdraw'],
            'is_ib_partner' => $input['is_ib_partner'],
            'is_internal_transfer' => $input['is_internal_transfer'],
            'is_external_transfer' => $input['is_external_transfer'],
            'is_bonus' => $input['is_bonus'],
            'status' => $input['status'],
            'priority' => $input['priority'],
            'icon' => $request->hasFile('icon') ? self::imageUploadTrait($input['icon']) : $schema->icon,
        ];
//        dd($finalData);

        $schema->update($finalData);

        notify()->success('schema Update successfully');

        return redirect()->route('admin.accountType.index');
    }
    public function destroy($id)
    {
        $schema = ForexSchema::findOrFail($id);
        if ($schema->forexAccounts()->exists()) {
            notify()->error('Cannot delete schema because there are Forex accounts associated with it.');
            return redirect()->route('admin.accountType.index');
        }
        $schema->delete();

        notify()->success('schema deleted successfully');

        return redirect()->route('admin.accountType.index');
    }
}
