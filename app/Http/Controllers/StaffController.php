<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Staff;
use Inertia\Inertia;
use Illuminate\Support\Facades\DB;
use App\Services\HistoryLogService;

class StaffController extends Controller
{   
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|max:255',
            'role' => 'required|string|max:255',
        ]);

        try {

            DB::beginTransaction();

            $staff = Staff::create([
                'user_id' => $request->user()->id,
                'name' => $validated['name'],
                'email' => $validated['email'],
                'role' => $validated['role'],
            ]);

            HistoryLogService::log(
                $request->user()->id,
                'Create Staff ' . $staff->name,
                'Staff',
                true,
                $request->ip()
            );

            DB::commit();

            return response()->json([
                'message' => 'Staff Created Successfully',
                'staff' => $staff
            ], 201);

        } catch (\Exception $e) {

            DB::rollBack();

            return response()->json([
                'message' => 'Failed to create staff',
                'error' => $e->getMessage() // remove in production
            ], 500);
        }
        //     //not much coding yet so idk what to see really, you can make  status an enum
        //     //hmm what else i think thats it. goodluck on your endeuvor
        //     //ouh one more, actually nvm since tak byk contoh
        //     //try do proper RBAC, then one each api like do check only allow if user have the roles
    }

    public function index()
    {
        $Staffs = Staff::orderBy('name', 'asc')->get();
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

    public function destroy(Request $request, Staff $staff)
    {
        try {

            DB::beginTransaction();

            $staff->delete(); // soft delete (if SoftDeletes enabled)

            HistoryLogService::log(
                $request->user()->id,
                'Delete Staff ' . $staff->name,
                'Staff',
                true,
                request()->ip()
            );

            DB::commit();

            return response()->json([
                'message' => 'Staff deleted successfully'
            ], 200);

        } catch (\Exception $e) {

            DB::rollBack();

            return response()->json([
                'message' => 'Failed to delete staff',
                'error' => $e->getMessage() // remove in production
            ], 500);
        }
    }

}
