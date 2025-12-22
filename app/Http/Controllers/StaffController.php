<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Staff;
use Inertia\Inertia;
use Illuminate\Support\Facades\DB;



class StaffController extends Controller
{   
    public function store(Request $request)
    {
        // validate input
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|max:255',
            'role' => 'required|string|max:255',
        ]);

        //should return/reject if validation failed

        // create ticket
        $staff = Staff::create([
            'user_id' => $request->user()->id,   // logged in user, i think this one can do like global interceptor, or like uhh i forgot, something like that yeah
            'name' => $validated['name'],
            'email' => $validated['email'],
            'role' => $validated['role'],
        ]);

        // send response
        return response()->json([
            'message' => 'Staff Created Successfully',
            'Staff' => $staff
        ], 201);

    //     //not much coding yet so idk what to see really, you can make  status an enum
    //     //hmm what else i think thats it. goodluck on your endeuvor
    //     //ouh one more, actually nvm since tak byk contoh
    //     //try do proper RBAC, then one each api like do check only allow if user have the roles
    }

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
