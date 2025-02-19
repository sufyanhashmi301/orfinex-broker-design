<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\TicketPriority;
use App\Services\TicketPriorityService;
use Illuminate\Http\Request;

class TicketPriorityController extends Controller
{

    protected $ticketPriorityService;

    public function __construct(TicketPriorityService $ticketPriorityService)
    {
        $this->middleware('permission:ticket-priority-list', ['only' => ['index']]);
        $this->middleware('permission:ticket-priority-create', ['only' => ['store']]);
        $this->middleware('permission:ticket-priority-edit', ['only' => ['update']]);
        $this->middleware('permission:ticket-priority-delete', ['only' => ['destroy']]);
        $this->ticketPriorityService = $ticketPriorityService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $priorities = $this->ticketPriorityService->getAll();
        return view('backend.ticket.priorities', compact('priorities'));
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
            'color' => 'required|string|max:255',
        ]);

        // Check if the status name already exists
        $existingStatus = TicketPriority::where('name', $request->name)->first();

        if ($existingStatus) {
            notify()->error(__('The priority name already exists.'));
            return redirect()->back();
        }

        $this->ticketPriorityService->create($data);
        notify()->success(__('Ticket priority created successfully.'));
        return redirect()->route('admin.ticket.priorities.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $priorities = $this->ticketPriorityService->getById($id);
        return response()->json($priorities);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $priority = $this->ticketPriorityService->getById($id);
        return view('backend.ticket.include.__edit_priority_form', compact('priority'))->render();
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
            'color' => 'required|string|max:255',
        ]);

        $this->ticketPriorityService->update($id, $data);
        notify()->success(__('Ticket priority updated successfully.'));
        return redirect()->route('admin.ticket.priorities.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $this->ticketPriorityService->delete($id);
        notify()->success(__('Ticket priority deleted successfully.'));
        return redirect()->route('admin.ticket.priorities.index');
    }
}
