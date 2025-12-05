<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Ticket;

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

        // create ticket
        $ticket = Ticket::create([
            'user_id' => $request->user()->id,   // logged in user
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
}
