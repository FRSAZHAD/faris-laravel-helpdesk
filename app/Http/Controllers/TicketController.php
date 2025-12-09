<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Ticket;
use Inertia\Inertia;
use Illuminate\Support\Facades\DB;



class TicketController extends Controller
{
    public function store(Request $request)
    {
        // validate input
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'category_id' => 'required|integer',
            'priority_id' => 'required|integer',
        ]);

        //should return/reject if validation failed

        // create ticket
        $ticket = Ticket::create([
            'user_id' => $request->user()->id,   // logged in user, i think this one can do like global interceptor, or like uhh i forgot, something like that yeah
            'title' => $validated['title'],
            'description' => $validated['description'],
            'category_id' => $validated['category_id'],
            'priority_id' => $validated['priority_id'],
            'status' => 'open',
        ]);

        // send response
        return response()->json([
            'message' => 'Ticket created successfully',
            'ticket' => $ticket
        ], 201);

    }

    public function index()
    {
        $tickets = Ticket::all();
        return response()->json(['tickets' => $tickets]);
    }

    public function show($id)
    {
        $ticket = Ticket::findOrFail($id);

        return Inertia::render('Ticket/TicketDetail', [
            'ticket' => $ticket
        ]);
    }

    public function showTicket($id)
    {
        $ticket = Ticket::findOrFail($id);
        return response()->json(['ticket' => $ticket]);
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

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:Open,Closed,Cancelled,On-Hold'
        ]);

        $ticket = Ticket::findOrFail($id);
        $ticket->status = $request->status;
        $ticket->save();

        return response()->json(['message' => 'Status updated', 'ticket' => $ticket],201);
    }

}
