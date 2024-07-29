<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\BlackListCountry;
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

class BlackListCountryController extends Controller
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
        $countries = BlackListCountry::orderBy('name','asc')->get();

        return view('backend.setting.country.black-list', compact('countries'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Application|Factory|View
     */
    public function create()
    {
        return view('backend.setting.country.create');
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
            'name' => 'required|unique:black_list_countries,name',
//            'priority' => 'required|integer|unique:black_list_countries,priority',
        ]);

        if ($validator->fails()) {
            notify()->error($validator->errors()->first(), 'Error');

            return redirect()->back();
        }

        $input = $request->all();

        $finalData = [
            'name' => $input['name'],

        ];

        BlackListCountry::create($finalData);

        notify()->success('Black List Country created successfully');

        return redirect()->route('admin.blackListCountry.index');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return Application|Factory|View
     */
    public function edit($id)
    {
        $schema = BlackListCountry::find($id);
        return view('backend.setting.country.edit', compact('schema'));
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
            'type' => Rule::unique('black_list_countries')->ignore($id),

        ]);

        if ($validator->fails()) {
            notify()->error($validator->errors()->first(), 'Error');

            return redirect()->back();
        }

        $schema = BlackListCountry::find($id);
        $input = $request->all();
//dd($input);
        $finalData = [
            'title' => $input['title'],
            'badge' => $input['badge'],
            'group' => $input['group'],
            'type' => $input['type'],
            'desc' => $input['desc'],

            'status' => $input['status'],
//            'priority' => $input['priority'],
            'icon' => $request->hasFile('icon') ? self::imageUploadTrait($input['icon']) : $schema->icon,
        ];
//        dd($finalData);

        $schema->update($finalData);

        notify()->success('Black List Country Update successfully');

        return redirect()->route('admin.blackListCountry.index');
    }

    public function destroy($id)
    {
//        dd($id);
        // Perform   the deletion logic here
        BlackListCountry::destroy($id);

        return redirect()->route('admin.blackListCountry.index')
            ->with('success', 'Country deleted successfully');
    }
}
