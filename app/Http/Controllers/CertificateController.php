<?php

namespace App\Http\Controllers;

use App\Models\Certificate;
use App\Services\GenerateCertificateService;
use App\Traits\ImageUpload;
use Illuminate\Http\Request;
use Database\Seeders\CertificateSeeder;
use Illuminate\Support\Facades\Artisan;

use Str;

class CertificateController extends Controller
{
    use ImageUpload;

    protected $generate_certificate_service;

    public function __construct(GenerateCertificateService $generate_certificate_service) {
        $this->generate_certificate_service = $generate_certificate_service;
    }

    public function runSeeder() {
        Artisan::call('db:seed', [
            '--class' => 'CertificateSeeder' 
        ]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $certificates = Certificate::all();

        // Run the seeder if DB is not initialized
        if(count($certificates) != CertificateSeeder::$TOTAL_CERTIFICATES) {
            $this->runSeeder();
            $certificates = Certificate::all();
        }

        
        return view('backend.certificates.index', compact('certificates'));
    }

    public function viewCertificate($id) {
        $certificate = Certificate::find($id);

        return view('backend.certificates.view_certificate', compact('certificate'));
    }

    public function updateConfig(Request $request) {

        $config_data = [
            'coordinate_x_name' => number_format($request->coordinate_x_name, 1),
            'coordinate_y_name' => number_format($request->coordinate_y_name, 1),
            'coordinate_x_date' => number_format($request->coordinate_x_date, 1),
            'coordinate_y_date' => number_format($request->coordinate_y_date, 1),
            "name_mention" => $request->name_mention,
            "name_font_size" => $request->name_font_size,
            "name_font_color" => $request->name_font_color,
            "date_font_size" => $request->date_font_size,
            "date_font_color" => $request->date_font_color,
        ];
        
        // saving before generating image generation as the field info is required when generating image
        $certificate = Certificate::find($request->certificate_id);
        $certificate->config = $config_data + ["example_certificate" => $certificate->config['example_certificate'] ?? ''];
        $certificate->save();

        // save again after generating the example url image
        $example_certificate = $this->generate_certificate_service->generateCertificate($request->certificate_id, 0, $config_data);
        $config_data = $config_data + ["example_certificate" => $example_certificate];
        $certificate->config = $config_data;
        $certificate->save();

        notify()->success('Certificate Template Updated Successfully');
        return redirect()->back();
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
        //
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
        //
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
        $certificate = Certificate::find($id);
    

        // if the image is not added and stored, admin cannot enable it
        if(!isset($request->image) && $certificate->image == '' && $request->is_enabled == 1) {
            notify()->error('Upload Certificate template to enable it.');
            return redirect()->back();
        }

        if(isset($request->image)) {
            $certificate->image = $this->imageUploadTrait($request->image);
        }
        $certificate->title = $request->title;
        $certificate->hook = $request->hook;
        $certificate->is_enabled = $request->is_enabled;
        $certificate->date_info = $request->date_info;
        $certificate->nickname_allowed = $request->nickname_allowed;
        $certificate->save();
        
        notify()->success('Certificate Updated Successfully');
        return redirect()->back();
        
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
