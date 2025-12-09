<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Staff;
use Inertia\Inertia;
use Illuminate\Support\Facades\DB;



class StaffController extends Controller
{   
    // public function store(Request $request)
    // {
    //     // validate input
    //     $validated = $request->validate([
    //         'title' => 'required|string|max:255',
    //         'description' => 'required|string',
    //         'category_id' => 'required|integer',
    //         'priority_id' => 'required|integer',
    //     ]);

    //     //should return/reject if validation failed

    //     // create ticket
    //     $ticket = staff::create([
    //         'user_id' => $request->user()->id,   // logged in user, i think this one can do like global interceptor, or like uhh i forgot, something like that yeah
    //         'title' => $validated['title'],
    //         'description' => $validated['description'],
    //         'category_id' => $validated['category_id'],
    //         'priority_id' => $validated['priority_id'],
    //         'status' => 'open',
    //     ]);

    //     // send response
    //     return response()->json([
    //         'message' => 'Ticket created successfully',
    //         'ticket' => $ticket
    //     ], 201);

    //     //not much coding yet so idk what to see really, you can make  status an enum
    //     //hmm what else i think thats it. goodluck on your endeuvor
    //     //ouh one more, actually nvm since tak byk contoh
    //     //try do proper RBAC, then one each api like do check only allow if user have the roles
    // }

    public function index()
    {
        $Staffs = Staff::all();
        return response()->json(['Staffs' => $Staffs]);
    }

     public function getRoles()
    {
        // Fetch the enum column definition from the database for the 'role' column
        $enum = DB::select("SHOW COLUMNS FROM staff LIKE 'role'");
        $type = $enum[0]->Type; // Example: enum('admin','agent','supervisor')

        // Extract the enum values from the string
        preg_match('/^enum\((.*)\)$/', $type, $matches);

        // Create an array from the enum values, removing extra spaces and quotes
        $roles = array_map(function ($value) {
            return trim($value, "'");
        }, explode(',', $matches[1]));

        return response()->json($roles);
    }

}
