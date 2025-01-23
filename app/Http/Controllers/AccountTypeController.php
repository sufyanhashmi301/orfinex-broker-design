<?php

namespace App\Http\Controllers;

use App\Models\AccountType;
use App\Enums\AccountType as AccountTypeEnum;
use App\Traits\ImageUpload;
use Illuminate\Http\Request;
use App\Enums\InterestPeriod;
use App\Models\AccountTypePhase;
use App\Services\AccountTypeService;
use App\Http\Requests\AccountTypeRequest;
use App\Models\AccountTypePhaseRule;
use App\Services\AccountTypePhaseService;

class AccountTypeController extends Controller
{
    use imageUpload;

    protected $accountTypeService;
    protected $accountTypePhaseService;

    public function __construct(AccountTypeService $accountTypeService, AccountTypePhaseService $accountTypePhaseService)
    {
        $this->accountTypeService = $accountTypeService;
        $this->accountTypePhaseService = $accountTypePhaseService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $account_types = AccountType::where('type', $request->type ?? 'challenge')->orderBy('priority', 'asc')->paginate(10);

        if(isset($request->type) && !in_array($request->type, [AccountTypeEnum::CHALLENGE, AccountTypeEnum::FUNDED, AccountTypeEnum::AUTO_EXPIRE])) {
            return redirect()->route('admin.account-type.index', ['type' => AccountTypeEnum::CHALLENGE]);
        }

        return view('backend.account_types.index', compact('account_types'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('backend.account_types.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(AccountTypeRequest $request)
    {
        // dd($request->all());

        // Step 1: Creating Account Type
        $input = $request->all();
        $account_type = $this->accountTypeService->createAccountType($input);

        // Step 2: Creating Account Type Phases with Rules | Attach them with Account Type
        $this->accountTypePhaseService->createPhases($account_type->id, $input['phases']); 

        notify()->success('Account Type Created Successfully');
        return redirect()->route('admin.account-type.index', ['type' => $request->type]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\AccountType  $accountType
     * @return \Illuminate\Http\Response
     */
    public function show(AccountType $accountType)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\AccountType  $accountType
     * @return \Illuminate\Http\Response
     */
    public function edit(AccountType $account_type)
    {

        return view('backend.account_types.edit')->with('account_type', $account_type);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\AccountType  $accountType
     * @return \Illuminate\Http\Response
     */
    public function update(AccountTypeRequest $request, AccountType $account_type)
    {
        // dd($request->all());

        // Update the account type itself
        $input = $request->all();
        $updated_account_type = $this->accountTypeService->updateAccountType($input, $account_type);

        // Update the phases / rules
        $this->accountTypePhaseService->updatePhases($account_type->id, $input['phases']); 

        notify()->success('Account Type Updated Successfully');
        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\AccountType  $accountType
     * @return \Illuminate\Http\Response
     */
    public function destroy(AccountType $accountType)
    {
        // Step 1: Retrieve all AccountTypePhases associated with the AccountType
        $accountTypePhases = $accountType->accountTypePhases;

        // Step 2: Loop through each AccountTypePhase and delete its rules
        foreach ($accountTypePhases as $phase) {
            $phase->accountTypePhaseRules()->delete(); // Delete all rules associated with this phase
        }

        // Step 3: Delete all AccountTypePhases
        $accountType->accountTypePhases()->delete();

        // Step 4: Delete the AccountType
        $accountType->delete();

        notify()->success('Account Type deleted Successfully!');
        return redirect()->back();
    }


}
