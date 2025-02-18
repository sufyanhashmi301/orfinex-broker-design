<?php

namespace App\Http\Controllers;

use ReflectionClass;
use App\Models\Setting;
use App\Models\KycMethod;
use Illuminate\Http\Request;
use App\Enums\KycNoticeInvokeEnums;
use Database\Seeders\KycMethodSeeder;
use Illuminate\Support\Facades\Artisan;

class KycMethodController extends Controller
{

    public function runSeeder() {
        Artisan::call('db:seed', [
            '--class' => 'KycMethodSeeder' 
        ]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $kyc_methods = KycMethod::all();
        $manual_method = KycMethod::where('slug', 'manual')->first();

        // KYC Notice
        $kyc_notice_invokes = (new ReflectionClass(KycNoticeInvokeEnums::class))->getConstants();
        $current_kyc_notice_invoke = kyc_invoke_at();

        // Run the seeder if DB is not initialized
        if(count($kyc_methods) != KycMethodSeeder::$TOTAL_KYC_METHODS) {
            $this->runSeeder();
            return redirect()->route('admin.settings.kyc');
        }

        return view('backend.setting.kyc.index', get_defined_vars());
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
        
        if($request->type == 'manual') {

            $manual_kyc_method = KycMethod::where('slug', 'manual')->first();

            $existing_options = $manual_kyc_method->data ?? [];
            $new_option = $request->only(['name', 'status', 'fields']);
            $existing_options[] = $new_option;
            
            $manual_kyc_method->data = $existing_options;
            $manual_kyc_method->save();

            notify()->success('Added new option to Manual KYC method');
        }

        return redirect()->back();
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
        
        if($request->type == 'manual') {
            $data = $request->data;
            
            $manual_method = KycMethod::where('slug', 'manual')->first();
            $manual_method->data = $data;
            $manual_method->save();
            
            notify()->success('Updated Manual KYC method');
        }

        return redirect()->back();
    }

    public function methodToggle(Request $request, $id) {
        if($request->action == 'active'){
            KycMethod::query()->update(['status' => 0]);
        }

        $kyc_method = KycMethod::find($id);
        $kyc_method->status = $request->action == 'active' ? 1 : 0;
        $kyc_method->save();

        if($request->action == 'active') {
            notify()->success('KYC Method Activated succesfully');
        } else {
            notify()->success('KYC Method Deactivated succesfully');
        }
        return redirect()->back();
    }

    public function optionToggle(Request $request, $action) {
        

        $manual_kyc_method = KycMethod::where('slug', 'manual')->first(); 
        $jsonData = $manual_kyc_method->data;
        
        $field_name = $request->field_name;

        $updatedJsonData = array_map(function ($item) use ($field_name, $action) {
            if ($item['name'] === $field_name) {
                $item['status'] = $action == 'active' ? 1 : 0; // Update the status
            }
            return $item;
        }, $jsonData);
    
        $manual_kyc_method->data = $updatedJsonData;
        $manual_kyc_method->save();

        notify()->success('Updated Manual KYC method');
        
  

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
        
    }

    public function deleteManualMethodField($option_name) {

        
        $manual_kyc_method = KycMethod::where('slug', 'manual')->first(); 
        $jsonData = $manual_kyc_method->data;

    
        $updatedJsonData = array_filter($jsonData, function ($item) use ($option_name) {
            return $item['name'] !== $option_name;
        });

    
        $manual_kyc_method->data = array_values($updatedJsonData);
        $manual_kyc_method->save();

        notify()->success('KYC Manual Method Option deleted successfully!');

        return redirect()->back();
        
    }
}
