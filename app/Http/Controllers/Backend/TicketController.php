<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Ticket;
use App\Models\Admin;
use App\Models\User;
use App\Models\Label;
use App\Models\Category;
use App\Traits\ImageUpload;
use App\Traits\NotifyTrait;
use DataTables;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;

class TicketController extends Controller
{
    use ImageUpload, NotifyTrait;

    /**
     * Display a listing of the resource.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('permission:support-ticket-list', ['only' => ['index']]);
        $this->middleware('permission:support-ticket-chat|support-ticket-assign', ['only' => ['closeNow', 'reply', 'show','showAssignModal']]);

    }

   public function index(Request $request, $id = null)
{
    $loggedInUser = auth()->user();
    $ticketQuery = Ticket::with('user', 'categories', 'labels', 'assignedToUser')->latest();

    // Get accessible user IDs using the helper (includes branch filtering)
    $accessibleUserIds = getAccessibleUserIds()->pluck('id')->toArray();

    // Ticket counts - filter by ticket creator's branch, not assigned staff
    if (!empty($accessibleUserIds)) {
        $totalTickets = Ticket::whereIn('user_id', $accessibleUserIds)->count();
        $closedTickets = Ticket::whereIn('user_id', $accessibleUserIds)->closed()->count();
        $openTickets = Ticket::whereIn('user_id', $accessibleUserIds)->opened()->count();
        $resolvedTickets = Ticket::whereIn('user_id', $accessibleUserIds)->resolved()->count();
    } elseif (!$loggedInUser->hasRole('Super-Admin')) {
        // If no accessible users and not Super-Admin, show no results
        $totalTickets = $closedTickets = $openTickets = $resolvedTickets = 0;
    } else {
        // Super-Admin sees all tickets
        $totalTickets = Ticket::count();
        $closedTickets = Ticket::closed()->count();
        $openTickets = Ticket::opened()->count();
        $resolvedTickets = Ticket::resolved()->count();
    }

    // Ajax call
    if ($request->ajax()) {
        if ($request->has('status') && !empty($request->status) && $request->status !== 'all') {
            if ($request->status === 'resolved') {
                $ticketQuery->where('is_resolved', true);
            } else {
                $ticketQuery->where('status', $request->status);
            }
        }

        // If a specific user id is provided via route, restrict to that user
        if (!is_null($id)) {
            $ticketQuery->where('user_id', $id);
        }

        // Apply user accessibility filter to ticket query based on ticket creator's branch
        if (!empty($accessibleUserIds)) {
            $ticketQuery->whereIn('user_id', $accessibleUserIds);
        } elseif (!$loggedInUser->hasRole('Super-Admin')) {
            // If no accessible users and not Super-Admin, show no results
            $ticketQuery->where('user_id', -1);
        }

        $data = $ticketQuery->get();

        return Datatables::of($data)
            ->addIndexColumn()
            ->addColumn('uuid', 'backend.ticket.include.__uuid')
            ->addColumn('title', 'backend.ticket.include.__title')
            ->addColumn('user', 'backend.ticket.include.__user')
            ->addColumn('assigned_to', function($ticket) {
                return $ticket->assignedToUser ? $ticket->assignedToUser->first_name.' '.$ticket->assignedToUser->last_name : 'Not assigned';
            })
            ->addColumn('status', 'backend.ticket.include.__status')
            ->addColumn('action', 'backend.ticket.include.__action')
            ->rawColumns(['uuid', 'title', 'user', 'assigned_to', 'status', 'action'])
            ->make(true);
    }

    return view('backend.ticket.all', compact('totalTickets', 'closedTickets', 'openTickets', 'resolvedTickets'));
}


    public function create()
    {
        $labels = Label::visible()->pluck('name', 'id');
        $categories = Category::visible()->pluck('name', 'id');
        $staff = Admin::where('status', true)->orderBy('first_name')->get();
        $users = User::orderBy('first_name')->get();

        return view('backend.ticket.include.__ticket_form', compact('labels', 'categories', 'staff', 'users'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required',
            'message' => 'required',
            'label' => 'required|exists:labels,id',
            'priority' => 'required',
            'assigned_to' => 'required|exists:admins,id',
            'user_id' => 'required|exists:users,id',
        ]);

        if ($validator->fails()) {
            notify()->error($validator->errors()->first(), __('Error'));
            return redirect()->back();
        }

        /** @var User $user */
        $user = User::findOrFail($request->input('user_id'));

        $input = $request->all();

        $data = [
            'uuid' => 'SUPT'.rand(100000, 999999),
            'title' => $input['title'],
            'priority' => $input['priority'],
            'assigned_to' => $input['assigned_to'],
            'user_id' => $input['user_id'],
            'message' => nl2br($input['message']),
        ];
        if ($request->hasFile('attach')) {
            try {
                $imagePath = self::imageUploadTrait($input['attach']);
            } catch (\Exception $e) {
                notify()->error($e->getMessage(), __('Error'));
                return redirect()->back()->withInput();
            }

            if (!$imagePath) {
                notify()->error(__('Image upload failed, please try again.'), __('Error'));
                return redirect()->back()->withInput();
            }

            $data['attach'] = $imagePath;
        } else {
            $data['attach'] = null;
        }

        if ($request->hasFile('attach') && !$data['attach']) {
            notify()->error(__('Image upload failed, please try again.'), __('Error'));
            return redirect()->back()->withInput();
        }

        $ticket = $user->tickets()->create($data);
        $ticket->attachLabels($request->input('label'));

        $shortcodes = [
            '[[full_name]]' => $user->full_name,
            '[[email]]' => $user->email,
            '[[subject]]' => $data['uuid'],
            '[[title]]' => $data['title'],
            '[[message]]' => $data['message'],
            '[[status]]' => 'OPEN',
            '[[site_title]]' => setting('site_title', 'global'),
            '[[site_url]]' => route('home'),
        ];

        if ($request->input('assigned_to')) {
            $ticket->assignTo($request->input('assigned_to'));
            $agent = Admin::find($request->input('assigned_to'));
            $this->mailNotify($agent->email, 'support_ticket_assignment', $shortcodes);
//            $this->pushNotify('support_ticket_assignment', $shortcodes, route('admin.ticket.show', $ticket->uuid), $agent->id);
        }

        $this->mailNotify($ticket->user->email, 'user_support_ticket', $shortcodes);
        $this->mailNotify(setting('support_email', 'global'), 'admin_support_ticket', $shortcodes);

        notify()->success(__('Your Ticket Was created successfully'), 'success');
        return Redirect::route('admin.ticket.show', $ticket->uuid);
    }

    public function show($uuid)
    {
        $labels = Label::visible()->pluck('name', 'id');
        $categories = Category::visible()->pluck('name', 'id');
        $staff = Admin::where('status', true)->orderBy('first_name')->get();

        $ticket = Ticket::uuid($uuid);
        return view('backend.ticket.show', compact('ticket', 'labels', 'categories', 'staff'));
    }

    public function showAssignModal(Ticket $ticket)
    {
        $staff = Admin::where('status', true)->orderBy('first_name')->get();
        return view('backend.ticket.include.__assign_form', compact('ticket', 'staff'));
    }

    public function assignTicket(Request $request, Ticket $ticket)
    {
        $request->validate([
            'assigned_to' => 'required|exists:admins,id',
        ]);

        $shortcodes = [
            '[[full_name]]' => $ticket->user->full_name,
            '[[email]]' => $ticket->user->email,
            '[[subject]]' => $ticket->uuid,
            '[[title]]' => $ticket->title,
            '[[message]]' => $ticket->message,
            '[[status]]' => 'OPEN',
            '[[site_title]]' => setting('site_title', 'global'),
            '[[site_url]]' => route('home'),
        ];

        $ticket->assignTo($request->assigned_to);
        $agent = Admin::find($request->assigned_to);
        $this->mailNotify($agent->email, 'support_ticket_assignment', $shortcodes);

        notify()->success('Ticket assigned successfully', 'success');
        return redirect()->back();
    }

    public function close(Ticket $ticket)
    {
        $this->authorize('update', $ticket);
        $ticket->close();

        notify()->success('Ticket closed successfully', 'success');
        return redirect()->back();
    }

    public function reopen(Ticket $ticket)
    {
        $this->authorize('update', $ticket);
        $ticket->reopen();

        notify()->success('Ticket reopen successfully', 'success');
        return redirect()->back();
    }

    public function archive(Ticket $ticket)
    {
        $this->authorize('update', $ticket);
        $ticket->archive();

        notify()->success('Ticket archived successfully', 'success');
        return redirect()->back();
    }

    public function resolve(Ticket $ticket)
    {
        $this->authorize('update', $ticket);
        $ticket->markAsResolved();

        notify()->success('Ticket marked as resolved successfully', 'success');
        return redirect()->back();
    }

    public function reply(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'message' => 'required',
        ]);

        if ($validator->fails()) {
            notify()->error($validator->errors()->first(), 'Error');
            return redirect()->back();
        }

        $input = $request->all();

        $adminId = \Auth::id();

        $data = [
            'model' => 'admin',
            'user_id' => $adminId,
            'message' => nl2br($input['message']),
//            'attach' => $request->hasFile('attach') ? self::imageUploadTrait($input['attach']) : null,
        ];
        if ($request->hasFile('attach')) {
            try {
                $imagePath = self::imageUploadTrait($input['attach']);
            } catch (\Exception $e) {
                // Handle exceptions during upload (e.g., size or extension issues)
                notify()->error($e->getMessage(), __('Error'));
                return redirect()->back()->withInput();
            }

            if (!$imagePath) {
                // If the image path is empty or upload fails
                notify()->error(__('Image upload failed, please try again.'), __('Error'));
                return redirect()->back()->withInput();
            }

            $data['attach'] = $imagePath;
        } else {
            $data['attach'] = null;
        }
//        dd($data['attach']);
        if ($request->hasFile('attach') && !$data['attach']) {
            // If the image path is empty or upload fails
            notify()->error(__('Image upload failed, please try again.'), __('Error'));
            return redirect()->back()->withInput();
        }
        $ticket = Ticket::uuid($input['uuid']);

        $ticket->messages()->create($data);

        $shortcodes = [
            '[[full_name]]' => $ticket->user->full_name,
            '[[email]]' => $ticket->user->email,
            '[[subject]]' => $input['uuid'],
            '[[title]]' => $ticket->title,
            '[[message]]' => $data['message'],
            '[[status]]' => $ticket->status,
            '[[site_title]]' => setting('site_title', 'global'),
            '[[site_url]]' => route('home'),
        ];

        $this->mailNotify($ticket->user->email, 'user_support_ticket_reply', $shortcodes);

        notify()->success('Ticket Reply successfully', 'success');

        return Redirect::route('admin.ticket.show', $ticket->uuid);

    }

    public function update(Request $request, Ticket $ticket)
    {
        $request->validate([
            'status' => 'nullable',
            'priority' => 'nullable',
            'label' => 'nullable',
            'assigned_to' => 'nullable',
        ]);

        $this->authorize('update', $ticket);

        $ticket->update($request->only('status', 'priority'));

        $ticket->syncLabels($request->label);

        if ($request->input('assigned_to')) {

            $ticket->assignTo($request->assigned_to);

            $shortcodes = [
                '[[full_name]]' => $ticket->user->full_name,
                '[[email]]' => $ticket->user->email,
                '[[subject]]' => $ticket->uuid,
                '[[title]]' => $ticket->title,
                '[[message]]' => $ticket->message,
                '[[status]]' => 'OPEN',
                '[[site_title]]' => setting('site_title', 'global'),
                '[[site_url]]' => route('home'),
            ];

            $agent = Admin::find($request->assigned_to);
            $this->mailNotify($agent->email, 'support_ticket_assignment', $shortcodes);

        }

        notify()->success('Ticket updated successfully', 'success');
        return redirect()->back();
    }

}
