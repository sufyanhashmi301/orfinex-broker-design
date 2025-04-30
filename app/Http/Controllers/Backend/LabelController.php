<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Label;
use App\Http\Requests\LabelRequest;

class LabelController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

     public function __construct()
     {
         $this->middleware('permission:ticket-type-list', ['only' => ['index']]);
         $this->middleware('permission:ticket-type-create', ['only' => ['store']]);
         $this->middleware('permission:ticket-type-edit', ['only' => ['update']]);
         $this->middleware('permission:ticket-type-delete', ['only' => ['destroy']]);
        
     }
    public function index()
    {
        $labels = Label::paginate();
        return view('backend.ticket.label', compact('labels'));
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
    public function store(LabelRequest $request)
    {
        Label::create($request->validated());
        return to_route('admin.ticket.label.index');
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
    public function edit(Label $label)
    {
        return view('backend.ticket.include.__edit_label_form', compact('label'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(LabelRequest $request, Label $label)
    {
        $label->update($request->validated());
        return to_route('admin.ticket.label.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Label $label)
    {
        $label->delete();
        return to_route('admin.ticket.label.index');
    }
}
