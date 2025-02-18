<?php

namespace App\Http\Controllers;

use App\Traits\NotifyTrait;
use Illuminate\Http\Request;
use App\Enums\StorageMethodEnums;
use Database\Seeders\StorageSeeder;
use App\Http\Requests\StorageRequest;
use App\Models\Storage as StorageModel;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Storage;

class StorageController extends Controller
{

    use NotifyTrait;

    public function runSeeder() {
        Artisan::call('db:seed', [
            '--class' => 'StorageSeeder' 
        ]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $storage_methods = StorageModel::all();
        // Run the seeder if DB is not initialized
        if(count($storage_methods) != StorageSeeder::$TOTAL_STORAGE_METHODS) {
            $this->runSeeder();
            return redirect()->route('admin.settings.storage.index');
        }
        
        $aws_storage_method = StorageModel::where('method', StorageMethodEnums::AWS_S3)->first();

        $aws_regions = [
            'us-east-2' => 'US East (Ohio) us-east-2',
            'us-east-1' => 'US East (N. Virginia) us-east-1',
            'us-west-1' => 'US West (N. California) us-west-1',
            'us-west-2' => 'US West (Oregon) us-west-2',
            'af-south-1' => 'Africa (Cape Town) af-south-1',
            'ap-east-1' => 'Asia Pacific (Hong Kong) ap-east-1',
            'ap-south-1' => 'Asia Pacific (Mumbai) ap-south-1',
            'ap-northeast-3' => 'Asia Pacific (Osaka-Local) ap-northeast-3',
            'ap-northeast-2' => 'Asia Pacific (Seoul) ap-northeast-2',
            'ap-southeast-1' => 'Asia Pacific (Singapore) ap-southeast-1',
            'ap-southeast-2' => 'Asia Pacific (Sydney) ap-southeast-2',
            'ap-northeast-1' => 'Asia Pacific (Tokyo) ap-northeast-1',
            'ca-central-1' => 'Canada (Central) ca-central-1',
            'eu-central-1' => 'Europe (Frankfurt) eu-central-1',
            'eu-west-1' => 'Europe (Ireland) eu-west-1',
            'eu-west-2' => 'Europe (London) eu-west-2',
            'eu-south-1' => 'Europe (Milan) eu-south-1',
            'eu-west-3' => 'Europe (Paris) eu-west-3',
            'eu-north-1' => 'Europe (Stockholm) eu-north-1',
            'me-south-1' => 'Middle East (Bahrain) me-south-1',
            'me-central-1' => 'Middle East (UAE) me-central-1',
            'sa-east-1' => 'South America (São Paulo) sa-east-1',
        ]; 

        // if admin didn't add any AWS credentials then don't let him activate this option
        if(getStorageMethod() == StorageMethodEnums::AWS_S3 && empty($aws_storage_method->details)) {
            StorageModel::query()->update(['status' => 0]);
            StorageModel::where('method', StorageMethodEnums::FILESYSTEM)->update(['status' => 1]);
        }

        return view('backend.setting.data_management.storage.index', get_defined_vars());
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
        
    }
    

    public function testAWS(Request $request) {
        
        $request->validate([
            'file' => 'required|file|mimes:jpeg,png,jpg,gif,svg,pdf,webp|max:5120', 
        ]);
        

        if ($request->file('file')) {
            $file = $request->file('file');
            $image_location = self::AWSUpload($file, 'admin/test');

            if (!$image_location) {
                return redirect()->back(); 
            }

            return redirect()->back()->with(['image_location' => $image_location]);
        }
        
    }

    public static function AWSUpload($file, $path) {
        
        try {
            
            $path = Storage::disk('s3')->put($path, $file); 
            $image_location = config('filesystems.disks.s3.url') . '/' . $path;
            return $image_location;
            
        } catch (\Exception $e) {
            notify()->error('Incorrect AWS S3 Credentials');
            return false;
        }

    }

    /**
     * Toggle Storage Method
     */
    public function toggleMethod(Request $request, $id) {
        
        if($request->action == 'active') {
            StorageModel::query()->update(['status' => 0]);

            StorageModel::find($id)->update(['status' => 1]);

            notify()->success('Storage method settings saved successfully!');
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
    public function update(StorageRequest $request)
    {

        if($request->method == 'aws_amazon_s3') {
            $details = $request->only(['aws_key', 'aws_secret', 'aws_bucket', 'aws_region']);
            
            $aws_storage_method = StorageModel::where('method', StorageMethodEnums::AWS_S3)->first();
            $aws_storage_method->details = $details;
            $aws_storage_method->save();

            // Update .env file
            setEnvironmentValue([
                'AWS_ACCESS_KEY_ID' => $details['aws_key'],
                'AWS_SECRET_ACCESS_KEY' => $details['aws_secret'],
                'AWS_BUCKET' => $details['aws_bucket'],
                'AWS_DEFAULT_REGION' => $details['aws_region'],
                'AWS_URL' => 'https://' . $details['aws_bucket'] . '.s3.' . $details['aws_region'] . '.amazonaws.com',
            ]);

            // Clear config cache
            Artisan::call('config:clear');
            Artisan::call('cache:clear');


            notify()->success('Storage settings updated successfully!');

        }

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
