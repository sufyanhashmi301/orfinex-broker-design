<?php

namespace App\Http\Controllers\Frontend;

use App\Enums\ContractStatusEnums;
use App\Models\Contract;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Http\Controllers\Controller;
use App\Models\Setting;
use App\Services\ContractService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;

class ContractController extends Controller
{

    protected $contract;

    public function __construct(ContractService $contract) {
        $this->contract = $contract;
    }

    private function assetsPath($path) {
        $public_path = public_path($path);
        $path = str_replace('public/', 'assets/', $public_path);
        $path = str_replace('public\\', 'assets/', $path);

        return $path;
    }

    public function adminIndex() {

        $contracts = Contract::all();

        $contract_expiry = Setting::where('name', 'contract_expiry')->first();

        if(!$contract_expiry) {
            $setting = new Setting();
            $setting->name = 'contract_expiry';
            $setting->val = 90;
            $setting->type = 'string';
            $setting->save();

            $contract_expiry = 90;
        } else {
            $contract_expiry = $contract_expiry->val;
        }

        return view('backend.contracts.index', compact('contracts', 'contract_expiry'));
    }

    public function markContractAs(Request $request) {
        
        if($request->action == 'sign') {
            // generate pdf with details without sign
            // change status
            // call signed in contract service
            // change account status
        }

        if($request->action == 'pending') {
            
        }

        if($request->action == 'expire') {
            
        }
    }

    public function config(Request $request) {
        $contract_expiry_setting = Setting::where('name', 'contract_expiry')->first();
        $contract_expiry_setting->val = $request->expiry;
        $contract_expiry_setting->save();

        notify()->success('Configured Successfully!');
        return redirect()->back();
    }

    public function index()
    {
        $this->contract->checkExpired();

        $contracts = Contract::where('user_id', Auth::id())->get();

        return view('frontend::contracts.index', compact('contracts'));
    }

    public function adminShow() {
        
    }

    public function show($id)
    {
        $user = auth()->user();

        $contract = Contract::where('user_id', Auth::id())->where('id', $id)->first();

        // if contract don't exist or doesn't belong to a logged in user
        if(!$contract) {
            abort(403);
        }

        // if contract is not pending
        if($contract->status != ContractStatusEnums::PENDING) {
            return redirect()->route('user.contracts');
        }

        $signature = '';
        $shouldHideElement = true;

        return view('frontend::contracts.show', compact('user', 'signature', 'shouldHideElement', 'contract'));
    }

    public function storeContract(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'signature' => 'required|string',
            'contract_id' => 'required|string'
        ]);

        if ($validator->fails()) {
            notify()->error($validator->errors()->first(), 'Error');

            return redirect()->back();
        }

        // get contract
        $contract = Contract::where('user_id', Auth::id())->where('id', $request->input('contract_id'))->first();

        // if contract don't exist or doesn't belong to a logged in user
        if(!$contract) {
            abort(403);
        }

        // if contract is not pending
        if($contract->status != ContractStatusEnums::PENDING) {
            return redirect()->route('user.contracts');
        }

        $user = auth()->user();
        $signature = $request->input('signature');

        $contractData = [
            'user' => $user,
            'signature' => $signature,
            'shouldHideElement' => false,
            'contract' => $contract
        ];

        $pdf = Pdf::loadView('frontend::contracts.include.__contract_template', $contractData);
        $fileName = 'contract_' . $user->id . '_' . time() . '.pdf';
        
        $directory = $this->assetsPath('frontend/user_contracts');

        if (!File::exists($directory)) {
            File::makeDirectory($directory, 0775, true);
        }

        try {
            $pdf_path = $pdf->save($directory . '/' . $fileName);
        } catch (\Exception $e) {
            notify()->error('There was an error generating the contract: ' . $e->getMessage(), 'Error');
            return redirect()->back();
        }

        // Update the contract
        $signed_contract = $this->contract->signed($contract, 'frontend/user_contracts/' . $fileName);

        if($signed_contract) {
            notify()->success('Contract Submitted Successfully!');
            return redirect()->route('user.contracts');
        } else {
            notify()->success('Unknown Error Occured!');
            return redirect()->route('user.contracts');
        }

        
    }
}
