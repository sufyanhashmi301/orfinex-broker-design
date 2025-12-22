<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Ticket;
use App\Models\Label;
use App\Models\Category;
use App\Models\User;
use App\Traits\ImageUpload;
use App\Traits\NotifyTrait;
use DataTables;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Redirect;
use App\Services\ActivityLogService;

class TicketController extends Controller
{
    use ImageUpload, NotifyTrait;

    public function index(Request $request)
    {

        $totalTickets = Ticket::where('user_id', Auth::id())->count();
        $closedTickets = Ticket::where('user_id', Auth::id())->closed()->count();
        $openTickets = Ticket::where('user_id', Auth::id())->opened()->count();
        $resolvedTickets = Ticket::where('user_id', Auth::id())->resolved()->count();
        $labels = Label::visible()->pluck('name', 'id');

        $ticketsQuery = Ticket::where('user_id', Auth::id());

        if ($request->filled('status') && $request->status !== 'all') {
            if ($request->status === 'resolved') {
                $ticketsQuery->where('is_resolved', true);
            } else {
                $ticketsQuery->where('status', $request->status);
            }
        }

        // For DataTables (desktop)
        if ($request->ajax() && $request->wantsJson()) {
            return Datatables::of($ticketsQuery)
                ->addIndexColumn()
                ->addColumn('title', 'frontend::ticket.include.__title')
                ->addColumn('status', 'frontend::ticket.include.__status')
                ->addColumn('action', 'frontend::ticket.include.__action')
                ->rawColumns(['title', 'status', 'action'])
                ->make(true);
        }

        // For mobile AJAX (HTML partial)
        if ($request->ajax()) {
            $tickets = $ticketsQuery->latest()->paginate(10)->appends($request->query());
            $html = view('frontend::ticket.include.__mobile_cards', compact('tickets'))->render();
            return response()->json(['html' => $html]);
        }

        $tickets = $ticketsQuery->latest()->paginate(10)->appends($request->query());

        return view('frontend::ticket.index', compact('totalTickets', 'closedTickets', 'openTickets', 'resolvedTickets', 'labels', 'tickets'));

    }

    public function newTicket()
    {
        $labels = Label::visible()->pluck('name', 'id');
        $categories = Category::visible()->pluck('name', 'id');

        return view('frontend::ticket.new', compact('labels', 'categories'));
    }

    public function store(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'title' => 'required',
            'message' => 'required',
            'label' => 'required|exists:labels,id',
            'priority' => 'required',
        ]);

        if ($validator->fails()) {
            notify()->error($validator->errors()->first(), __('Error'));

            return redirect()->back();
        }

        /** @var User $user */
        $user = Auth::user();

        $input = $request->all();

        $data = [
            'uuid' => 'SUPT'.rand(100000, 999999),
            'title' => $input['title'],
            'priority' => $input['priority'],
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

        $this->mailNotify($ticket->user->email, 'user_support_ticket', $shortcodes);
        $this->mailNotify(setting('support_email', 'global'), 'admin_support_ticket', $shortcodes);

        ActivityLogService::log('ticket_create', "Created new support ticket", [
            'Ticket Title' => $data['title'],
            'Ticket Message' => $data['message'],
            'Ticket Priority' => $data['priority'],
            'Ticket Labels' => Label::find($request->input('label'))->pluck('name')->implode(', '),
            'Ticket Attachments' => $data['attach'] ? 'Yes' : 'No',
        ]);
        notify()->success(__('Your Ticket Was created successfully'), 'success');

        return Redirect::route('user.ticket.show', $ticket->uuid);

    }

    public function show($uuid)
    {

        $ticket = Ticket::uuid($uuid)->close();

        if ($ticket->isClosed()) {
            $ticket->reopen();
        }

        return view('frontend::ticket.show', compact('ticket'));
    }

    public function closeNow($uuid)
    {

        Ticket::uuid($uuid)->close();
        
        ActivityLogService::log('ticket_close', "Closed support ticket", [
            'Ticket Title' => Ticket::uuid($uuid)->title,
            'Ticket Status' => Ticket::uuid($uuid)->status,
        ]);

        notify()->success(__('Your Ticket Closed successfully'), 'success');

        return Redirect::route('user.ticket.index');

    }

    public function reply(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'message' => 'required',
        ]);

        if ($validator->fails()) {
            notify()->error($validator->errors()->first(), __('Error'));

            return redirect()->back();
        }

        /** @var User $user */
        $user = Auth::user();
        $input = $request->all();
        $data = [
            'user_id' => $user->id, // @phpstan-ignore-line
            'message' => nl2br($input['message']),
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
            '[[full_name]]' => $user->full_name,
            '[[email]]' => $user->email,
            '[[subject]]' => $input['uuid'],
            '[[title]]' => $ticket->title,
            '[[message]]' => $data['message'],
            '[[status]]' => $ticket->status,
            '[[site_title]]' => setting('site_title', 'global'),
            '[[site_url]]' => route('home'),
        ];

        $this->mailNotify(setting('support_email', 'global'), 'admin_support_ticket', $shortcodes);

        ActivityLogService::log('ticket_reply', "Replied to support ticket", [
            'Ticket Title' => $ticket->title,
            'Ticket Message' => $data['message'],
            'Ticket Attachments' => $data['attach'] ? 'Yes' : 'No',
        ]);

        notify()->success(__('Your Ticket Reply successfully'), 'success');

        return Redirect::route('user.ticket.show', $ticket->uuid);

    }
}
