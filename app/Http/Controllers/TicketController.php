<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Ticket;
use Inertia\Inertia;
use Illuminate\Support\Facades\DB;
use App\Providers\RunningNumberService;
use App\Services\HistoryLogService;

class TicketController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'category_id' => 'required|integer',
            'staff_id' => 'required|integer',
            'priority_id' => 'required|integer',
        ]);

        try {

            DB::beginTransaction();

            $ticketNo = RunningNumberService::generateTicketNo();

            $ticket = Ticket::create([
                'user_id' => $request->user()->id,
                'ticket_id' => $ticketNo,
                'title' => $validated['title'],
                'description' => $validated['description'],
                'staff_id' => $validated['staff_id'],
                'category_id' => $validated['category_id'],
                'priority_id' => $validated['priority_id'],
                'status' => 'open',
            ]);

            HistoryLogService::log(
                $request->user()->id,
                'Create Ticket #' . $ticket->ticket_id,
                'Ticket',
                true,
                $request->ip()
            );

            DB::commit();

            return response()->json([
                'message' => 'Ticket created successfully',
                'ticket' => $ticket
            ], 201);

        } catch (\Exception $e) {

            DB::rollBack();

            return response()->json([
                'message' => 'Failed to create ticket',
                'error' => $e->getMessage() // remove in production
            ], 500);
        }
    }

    public function index()
    {
        $tickets = Ticket::with(['staff', 'category'])->latest()->get();

        return response()->json([
            'tickets' => $tickets
        ]);
    }

    public function show($id)
    {
        
        return Inertia::render('Ticket/TicketDetail');
    }

    public function showTicket($id)
    {
        $ticket = Ticket::with(['staff','category','histories' => function ($q) {$q->latest()->with(['staff', 'category']);},
        ])->findOrFail($id);

        return response()->json([
            'ticket' => $ticket
        ]);
    }

    public function getStatusOptions()
    {
        $enum = DB::select("SHOW COLUMNS FROM tickets LIKE 'status'");
        $type = $enum[0]->Type; // Example: enum('open','in_progress','pending','resolved','closed')

        preg_match('/^enum\((.*)\)$/', $type, $matches);

        $options = array_map(function ($value) {
            return trim($value, "'");
        }, explode(',', $matches[1]));

        return response()->json([
            'status_options' => $options
        ]);
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'category_id' => 'required|exists:category,id',
            'priority_id' => 'required|integer',
            'staff_id' => 'required|exists:staff,id',
            'status' => 'required',
        ]);

        $ticket = Ticket::findOrFail($id);
        $ticket->update($validated);
        $ticket->load(['staff', 'category']);
        
        HistoryLogService::log(
            $request->user()->id,
            'Update Ticket #' . $ticket->ticket_id,
            'Ticket',
            true,
            $request->ip()
        );

        return response()->json([
            'message' => 'Ticket updated',
            'ticket' => $ticket,
        ]);
    }

    public function dashboard()
    {
        return response()->json([
            'totalTickets' => Ticket::count(),
            'openTickets' => Ticket::where('status', 'Open')->count(),
            'onHoldTickets' => Ticket::where('status', 'On-Hold')->count(),
            'closedTickets' => Ticket::where('status', 'Closed')->count(),
            'cancelledTickets' => Ticket::where('status', 'Cancelled')->count(),
            'recentTickets' => Ticket::latest()
                ->limit(5)
                ->get(['id', 'title', 'status', 'created_at']),
        ]);
    }

    public function histories(Ticket $ticket)
    {
        return $ticket->histories()
            ->latest()
            ->get();
    }

    public function storeHistory(Request $request, Ticket $ticket)
    {
        $validated = $request->validate([
            'description' => 'required|string',
            'staff_id' => 'required|exists:staff,id',
            'category_id' => 'required|exists:category,id',
            'status' => 'required|string',
            'attachment' => 'nullable|string',
        ]);

        try {

            DB::beginTransaction();

            $history = $ticket->histories()->create([
                'description' => $validated['description'],
                'status' => $validated['status'],
                'attachment' => $validated['attachment'] ?? null,
                'staff_id' => $validated['staff_id'],
                'category_id' => $validated['category_id'],
                'created_by' => $request->user()->id,
            ]);

            $ticket->update([
                'status' => $validated['status'],
                'staff_id' => $validated['staff_id'],
                'category_id' => $validated['category_id'],
            ]);

            HistoryLogService::log(
                $request->user()->id,
                'Update Ticket #' . $ticket->ticket_id . ' history',
                'Ticket',
                true,
                $request->ip()
            );

            DB::commit();

            return response()->json([
                'message' => 'History added and ticket status updated',
                'history' => $history->load(['staff', 'category']),
            ], 201);

        } catch (\Exception $e) {

            DB::rollBack();

            return response()->json([
                'message' => 'Failed to add history',
                'error' => $e->getMessage() // remove in production
            ], 500);
        }
    }
    // public function updateTicket(Request $request, $id)
    // {
    //     $request->validate([
    //         'status' => 'required|in:Open,Closed,Cancelled,On-Hold'
    //     ]);

    //     $ticket = Ticket::findOrFail($id);
    //     $ticket->status = $request->status;
    //     $ticket->save();

    //     $ticket->load('staff');

    //     return response()->json([
    //         'message' => 'updated',
    //         'ticket' => $ticket
    //     ]);
    // }

    // public function updateStaff(Request $request, $id)
    // {
    //     $request->validate([
    //         'staff_id' => 'required|exists:staff,id',
    //     ]);

    //     $ticket = Ticket::findOrFail($id);
    //     $ticket->staff_id = $request->staff_id;
    //     $ticket->save();

    //     $ticket->load('staff');

    //     return response()->json([
    //         'message' => 'Staff updated',
    //         'ticket' => $ticket,
    //     ]);
    // }

}
