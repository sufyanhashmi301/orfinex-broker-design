<?php

namespace App\Http\Controllers\Backend;

use App\Models\User;
use App\Models\Bonus;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreBonusRequest;
use App\Http\Requests\UserAssignBonusRequest;
use App\Models\ForexSchema;
use App\Services\BonusService;

class BonusController extends Controller
{

    protected $bonusService;

    public function __construct(BonusService $bonusService) {
        $this->bonusService = $bonusService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $bonuses = Bonus::all();
        return view('backend.bonus.index', compact('bonuses'));
    }

    /** 
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $forex_account_types = ForexSchema::where('status', 1)->with('bonus')->get();
        return view('backend.bonus.create', compact('forex_account_types'));
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

        $this->bonusService->assignBonusToAccountType($bonus->id, $request->forex_account_types);

        notify()->success(__('Bonus created successfully.'));
        return redirect()->route('admin.bonus.index');

    }

    /**
     * Adding bonus to the user's account
     */
    public function addBonus(UserAssignBonusRequest $request, User $user){
        $response = $this->bonusService->addManualBonus($request, $user);
        
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
        $forex_account_types = ForexSchema::where('status', 1)->with('bonus')->get();

        return view('backend.bonus.create')
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
    public function update(StoreBonusRequest $request, $bonus)
    {
        Bonus::findorFail($bonus)->update($request->validated());

        $this->bonusService->assignBonusToAccountType($bonus, $request->forex_account_types);

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
