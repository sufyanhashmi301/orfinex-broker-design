<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreBonusRequest;
use App\Models\Bonus;
use Illuminate\Http\Request;

class BonusController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $bonuses = Bonus::all();
        return view('backend.bonus.index')->with('bonuses', $bonuses);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('backend.bonus.create');
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

        notify()->success(__('Bonus created successfully.'));
        return redirect()->route('admin.bonus.index')->with('success', 'Bonus created successfully.');

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
    public function edit($bonus)
    {
        return view('backend.bonus.create')->with('bonus', Bonus::findorFail($bonus));
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

        notify()->success(__('Bonus updated successfully.'));
        return redirect()->back();
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
