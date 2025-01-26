<?php

namespace App\Http\Controllers\Frontend;

use App\Enums\ContractStatusEnums;
use App\Models\Contract;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Setting;
use App\Services\ContractService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ContractController extends Controller
{

    protected $contract;

    public function __construct(ContractService $contract) {
        $this->contract = $contract;
    }

    

    public function adminIndex(Request $request) {

        // Contract Paramater
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

        $contracts_filter = false;
        // Filter accounts wrt status when status exists
        if(isset($request->status)){

            if (in_array($request->status, (new \ReflectionClass(ContractStatusEnums::class))->getConstants())) {
                // Handle the logic here if the status is valid
                $contracts = Contract::where('status', $request->status)->orderBy('id', 'desc')->paginate(15);
                $title = ucfirst( str_replace('contract_', '', $request->status) ) . ' Contracts';
                $contracts_filter = true;
            }

        }

        // if status is unknown then show all accounts
        if(!$contracts_filter) {
            $contracts = Contract::orderBy('id', 'desc')->paginate(15);
            $title = 'All Contracts';
            if($request->status != 'all') {
                return redirect()->route('admin.contracts.index', ['status' => 'all']);
            }
        }

        return view('backend.contracts.index', compact('contracts', 'contract_expiry', 'title'));
    }

    public function markContractAs(Request $request) {

        $contract = Contract::whereIn('status', [ContractStatusEnums::PENDING, ContractStatusEnums::EXPIRED])->where('id', $request->contract_id)->firstorFail();
        
        if($request->action == 'sign') {
            
            // generate pdf with details without sign
            $contract_pdf = $this->contract->generateContract($contract->id, '');

            // mark as signed
            $signed_contract = $this->contract->signed($contract, $contract_pdf['file_path']);

            notify()->success('Contract with Account Login #' . $signed_contract->accountTypeInvestment->login . ' has been signed successfully!');

        }

        if($request->action == 'pending') {
            // mark as expired
            $pending_contract = $this->contract->pending($contract);

            notify()->success('Contract with Account Login #' . $pending_contract->accountTypeInvestment->login . ' marked as pending successfully!');
        }

        if($request->action == 'expire') {
            // mark as expired
            $expired_contract = $this->contract->expired($contract);

            notify()->success('Contract with Account Login #' . $expired_contract->accountTypeInvestment->login . ' has been expired successfully!');
        }

        return redirect()->back();
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

        // Validation ---
        $validator = Validator::make($request->all(), [
            'signature' => 'required|string',
            'contract_id' => 'required|string'
        ]);
        if ($validator->fails()) {
            notify()->error($validator->errors()->first(), 'Error');
            return redirect()->back();
        }
        // Validation ---

        // get contract and if contract don't exist or doesn't belong to a logged in user
        $contract = Contract::where('user_id', Auth::id())->where('id', $request->input('contract_id'))->first();
        abort_if(!$contract, 403);

        // if contract is not pending
        if($contract->status != ContractStatusEnums::PENDING) {
            return redirect()->route('user.contracts');
        }

        // Generate Contract
        $contract_pdf = $this->contract->generateContract($request->input('contract_id'), $request->input('signature'));

        // Update the contract
        $signed_contract = $this->contract->signed($contract, $contract_pdf['file_path']);
        
        notify()->success('Contract Submitted Successfully!');
        return redirect()->route('user.contracts');

        
    }
}
