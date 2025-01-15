<?php

namespace App\Http\Controllers;

use App\Models\Addon;
use Database\Seeders\AddonSeeder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;

class AddonController extends Controller
{
    public function runSeeder() {
        Artisan::call('db:seed', [
            '--class' => 'AddonSeeder' 
        ]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $addons = Addon::all(); 

        // Run the seeder if DB is not initialized
        if(count($addons) != AddonSeeder::$TOTAL_ADDONS) {
            $this->runSeeder();
            $addons = Addon::all(); 
        }

        return view('backend.addons.index', compact('addons'));
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
    public function update(Request $request)
    {
        $addon = Addon::findOrFail($request->addon_id);

        $addon->name = $request->name;
        $addon->description = $request->description;
        $addon->amount_type = $request->amount_type;
        $addon->amount = $request->amount;
        $addon->status = $request->status;
        $addon->save();

        notify()->success('Addon updated successfully!');
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
