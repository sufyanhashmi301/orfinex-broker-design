<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\IbSchema;
use App\Models\Schedule;
use App\Traits\ImageUpload;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class IBSchemaController extends Controller
{
    use ImageUpload;

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function __construct()
    {
       $this->middleware('permission:schema-list|schema-create|schema-edit', ['only' => ['index', 'store']]);
       $this->middleware('permission:schema-create', ['only' => ['create', 'store']]);
       $this->middleware('permission:schema-edit', ['only' => ['edit', 'update']]);
       $this->middleware('permission:schema-delete', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return Application|Factory|View
     */
    public function index()
    {
        $schemas = IbSchema::orderBy('priority','asc')->paginate(10);

        return view('backend.ib_schema.index', compact('schemas'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Application|Factory|View
     */
    public function create()
    {
        return view('backend.ib_schema.create');
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
            'group' => 'required',
            'type' => 'required|unique:ib_schemas,type',
//            'priority' => 'required|integer|unique:ib_schemas,priority',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator->errors())->withInput();
        }

        $input = $request->all();

        $input['desc'] = str_replace(['{', '}'], ['<', '>'], $request->desc);

        $finalData = [
            'title' => $input['title'],
            'badge' => $input['badge'],
            'group' => $input['group'],
            'type' => $input['type'],
            'desc' => $input['desc'],
            'status' => $input['status'],
//            'priority' => $input['priority'],

        ];

        IbSchema::create($finalData);

        notify()->success('ib schema created successfully');

        return redirect()->route('admin.ibAccountType.index');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return Application|Factory|View
     */
    public function edit($id)
    {
        $schema = IbSchema::find($id);
        return view('backend.ib_schema.edit', compact('schema'));
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
            'group' => 'required',
            'type' => Rule::unique('ib_schemas')->ignore($id),

        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator->errors())->withInput();
        }

        $schema = IbSchema::find($id);
        $input = $request->all();

        $input['desc'] = str_replace(['{', '}'], ['<', '>'], $request->desc);

//dd($input);
        $finalData = [
            'title' => $input['title'],
            'badge' => $input['badge'],
            'group' => $input['group'],
            'type' => $input['type'],
            'desc' => $input['desc'],

            'status' => $input['status'],
//            'priority' => $input['priority'],
        ];
//        dd($finalData);

        $schema->update($finalData);

        notify()->success('schema Update successfully');

        return redirect()->route('admin.ibAccountType.index');
    }
    public function destroy($id)
    {
        $ibSchema = IbSchema::findOrFail($id);

        $ibSchema->delete();

        notify()->success('schema deleted successfully');

        return redirect()->route('admin.ibAccountType.index');
    }
}
