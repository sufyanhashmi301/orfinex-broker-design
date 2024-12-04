<?php

namespace App\Http\Controllers\Backend;

use App\Models\User;
use App\Models\Bonus;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreBonusRequest;
use App\Http\Requests\UserAssignBonusRequest;
use App\Models\ForexSchema;
use App\Models\KycLevel;
use App\Services\BonusService;

class BonusController extends Controller
{

    protected $bonusService;
    protected $kyc_levels;

    public function __construct(BonusService $bonusService) {
        $this->middleware('permission:bonus-list', ['only' => ['index']]);
        $this->middleware('permission:bonus-create', ['only' => ['store']]);
        $this->middleware('permission:bonus-edit', ['only' => ['update']]);
        $this->middleware('permission:bonus-delete', ['only' => ['destroy']]);
        $this->bonusService = $bonusService;
        $this->kyc_levels = KycLevel::where('status', 1)->get();;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $bonuses = Bonus::with('forex_schemas')->get();
        return view('backend.bonus.index', compact('bonuses'));
    }

    /** 
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $forex_account_types = ForexSchema::where('status', 1)->with('bonuses')->get();
        $kyc_levels = $this->kyc_levels;
        return view('backend.bonus.create', compact('forex_account_types', 'kyc_levels'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreBonusRequest $request)
    {
        $bonus = Bonus::create($request->validated());

        $this->bonusService->assignBonusToAccountType($bonus, $request->forex_account_types);

        notify()->success(__('Bonus created successfully.'));
        return redirect()->route('admin.bonus.index');

    }

    /**
     * Adding bonus to the user's account
     */
    public function addBonusByAdmin(UserAssignBonusRequest $request, User $user){
        $response = $this->bonusService->addBonus($request, $user);
        
        $response['status'] == 'error' ? notify()->error($response['message'], $response['status']) : notify()->success($response['message'], $response['status']);

        return redirect()->back();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Bonus  $bonus
     * @return \Illuminate\Http\Response
     */
    public function show(Bonus $bonus)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Bonus  $bonus
     * @return \Illuminate\Http\Response
     */
    public function edit($bonus_id)
    {
        $forex_account_types = ForexSchema::where('status', 1)->with('bonuses')->get();
        $kyc_levels = $this->kyc_levels;
        return view('backend.bonus.create', compact('kyc_levels'))
        ->with([
                'bonus' => Bonus::where('id', $bonus_id)->with('forex_schemas')->first(), 
                'forex_account_types' => $forex_account_types
            ]);
    }
    

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Bonus  $bonus
     * @return \Illuminate\Http\Response
     */
    public function update(StoreBonusRequest $request,$bonus)
    {
        $bonus = (int) $bonus;

        Bonus::find($bonus)->update($request->validated());

        $this->bonusService->assignBonusToAccountType(Bonus::find($bonus), $request->forex_account_types);

        notify()->success(__('Bonus updated successfully.'));
        return redirect()->route('admin.bonus.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Bonus  $bonus
     * @return \Illuminate\Http\Response
     */
    public function destroy($bonus)
    {
        Bonus::destroy($bonus);

        notify()->success(__('Bonus deleted successfully.'));
        return redirect()->back();
    }
}
