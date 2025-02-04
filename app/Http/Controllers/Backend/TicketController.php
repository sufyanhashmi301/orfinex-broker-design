<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Ticket;
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
        $this->middleware('permission:support-ticket-list|support-ticket-action', ['only' => ['index']]);
        $this->middleware('permission:support-ticket-action', ['only' => ['closeNow', 'reply', 'show']]);

    }

    public function index(Request $request, $id = null)
    {
        $loggedInUser = auth()->user();

        if ($request->ajax()) {
            // Check if the logged-in user is a Super-Admin
            if ($loggedInUser->hasRole('Super-Admin')) {
                if ($id) {
                    // Fetch tickets for a specific user (if ID is provided)
                    $data = Ticket::where('user_id', $id)->latest();
                } else {
                    // Fetch all tickets
                    $data = Ticket::query()->latest();
                }
            } else {
                // Get attached user IDs for non-Super-Admin users
                $attachedUserIds = $loggedInUser->users->pluck('id');

                if ($attachedUserIds->isNotEmpty()) {
                    if ($id) {
                        // Fetch tickets for the specified user and ensure they are attached
                        $data = Ticket::where('user_id', $id)
                            ->whereIn('user_id', $attachedUserIds)
                            ->latest();
                    } else {
                        // Fetch tickets for attached users only
                        $data = Ticket::whereIn('user_id', $attachedUserIds)->latest();
                    }
                } else {
                    // If no users are attached, return an empty collection
                    $data = collect(); // Empty collection
                }
            }

            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('name', 'backend.ticket.include.__name')
                ->addColumn('priority', 'backend.ticket.include.__priority')
                ->addColumn('status', 'backend.ticket.include.__status')
                ->addColumn('action', 'backend.ticket.include.__action')
                ->rawColumns(['name', 'priority', 'status', 'action'])
                ->make(true);
        }

        return view('backend.ticket.all');
    }


    public function show($uuid)
    {
        $ticket = Ticket::uuid($uuid);

        return view('backend.ticket.show', compact('ticket'));
    }

    public function closeNow($uuid)
    {
        Ticket::uuid($uuid)->close();
        notify()->success('Ticket Closed successfully', 'success');

        return Redirect::route('admin.ticket.index');

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

    public function ticketStatus() {
        return view('backend.ticket.status');
    }

    public function ticketPriority() {
        return view('backend.ticket.priority');
    }

}
