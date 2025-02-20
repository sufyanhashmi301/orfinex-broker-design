<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\DealNote;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class DealNoteController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'deal_id' => 'required|exists:leads,id',
            'details' => 'required',
        ]);
        $data['added_by'] = auth()->id();

        $note = DealNote::create($data);

        notify()->success(__('Deal updated successfully.'));
        return redirect()->route('admin.deal.show', ['id' => $data['deal_id']]);
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
        $note = DealNote::findOrFail($id);
        return view('backend.deals.include.__note_edit_form', compact('note'));
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
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'details' => 'required|string',
        ]);

        if ($validator->fails()) {
            notify()->error($validator->errors()->first(), __('Error'));
            return redirect()->back();
        }

        $data = $validator->validated();

        $note = DealNote::findOrFail($id);

        $note->update($data);
        notify()->success(__('Note updated successfully.'));
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
        $note = DealNote::findOrFail($id);

        $note->delete();
        notify()->success(__('Note deleted successfully.'));
        return redirect()->back();
    }
}
