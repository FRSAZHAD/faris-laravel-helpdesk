<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Staff;
use Inertia\Inertia;
use Illuminate\Support\Facades\DB;



class StaffController extends Controller
{

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
