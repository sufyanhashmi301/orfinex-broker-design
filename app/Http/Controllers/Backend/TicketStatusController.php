<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\TicketStatus;
use App\Services\TicketStatusService;
use Illuminate\Http\Request;

class TicketStatusController extends Controller
{

    protected $ticketStatusService;

    public function __construct(TicketStatusService $ticketStatusService)
    {
        $this->ticketStatusService = $ticketStatusService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $statuses = $this->ticketStatusService->getAll();
        return view('backend.ticket.statuses', compact('statuses'));
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
            'name' => 'required|string|max:255',
            'status_type' => 'required|string|max:255'
        ]);

        // Check if the status name already exists
        $existingStatus = TicketStatus::where('name', $request->name)->first();

        if ($existingStatus) {
            notify()->error(__('The status name already exists.'));
            return redirect()->back();
        }

        $this->ticketStatusService->create($data);
        notify()->success(__('Ticket status created successfully.'));
        return redirect()->route('admin.ticket.statuses.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $status = $this->ticketStatusService->getById($id);
        return view('ticket_statuses.show', compact('status'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $status = $this->ticketStatusService->getById($id);
        return view('backend.ticket.include.__edit_status_form', compact('status'))->render();
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
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'status_type' => 'required|string|max:255'
        ]);

        $this->ticketStatusService->update($id, $data);
        notify()->success(__('Ticket status updated successfully.'));
        return redirect()->route('admin.ticket.statuses.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $this->ticketStatusService->delete($id);
        notify()->success(__('Ticket status deleted successfully'));
        return redirect()->route('admin.ticket.statuses.index');
    }
}
