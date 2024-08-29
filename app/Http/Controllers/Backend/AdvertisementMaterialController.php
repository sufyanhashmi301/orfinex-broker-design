<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\AdvertisementMaterial;
use App\Models\Language;
use App\Models\Schedule;
use App\Traits\ImageUpload;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;

class AdvertisementMaterialController extends Controller
{
    use ImageUpload;

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function __construct()
    {
//        $this->middleware('permission:advertisement-material-list|advertisement-material-create|advertisement-material-edit', ['only' => ['index', 'store']]);
//        $this->middleware('permission:advertisement-material-create', ['only' => ['create', 'store']]);
//        $this->middleware('permission:advertisement-material-edit', ['only' => ['edit', 'update']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return Application|Factory|View
     */
    public function index()
    {
        $advertisements = AdvertisementMaterial::paginate(10);

        return view('backend.advertisement_material.index', compact('advertisements'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Application|Factory|View
     */
    public function create()
    {
        $languages = Language::where('status', true)->get();

        return view('backend.advertisement_material.create',compact('languages'));
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
            'img' => 'required',
            'language' => 'required',
            'type' => 'required',
        ]);

        if ($validator->fails()) {
            notify()->error($validator->errors()->first(), 'Error');

            return redirect()->back();
        }

        $input = $request->all();

        $finalData = [
            'language' => $input['language'],
            'type' => $input['type'],
            'status' => $input['status'],
            'img' => self::imageUploadTrait($input['img']),
        ];
//        dd($finalData);


        AdvertisementMaterial::create($finalData);

        notify()->success('Advertisement Material created successfully');

        return redirect()->route('admin.advertisement_material.index');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return Application|Factory|View
     */
    public function edit($id)
    {
        $advertisement = AdvertisementMaterial::find($id);
        $languages = Language::where('status', true)->get();

        return view('backend.advertisement_material.edit', compact('advertisement','languages'));
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
//            'img' => 'required',
            'language' => 'required',
            'type' => 'required',
        ]);

        if ($validator->fails()) {
            notify()->error($validator->errors()->first(), 'Error');

            return redirect()->back();
        }

        $advertisement = AdvertisementMaterial::find($id);
        $input = $request->all();
//dd($input);
        $finalData = [
            'language' => $input['language'],
            'type' => $input['type'],
            'status' => $input['status'],

            'icon' => $request->hasFile('img') ? self::imageUploadTrait($input['img']) : $advertisement->img,
        ];
//        dd($finalData);

        $advertisement->update($finalData);

        notify()->success('Advertisement Material Update successfully');

        return redirect()->route('admin.advertisement_material.index');
    }
}
