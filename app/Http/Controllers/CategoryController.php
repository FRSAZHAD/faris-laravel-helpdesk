<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Inertia\Inertia;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index()
    {   
        $Category = Category::orderBy('category', 'asc')->get();
        return response()->json(['category' => $Category]);
    }

    public function store(Request $request)
    {
        // validate input
        $validated = $request->validate([
            'category' => 'required|string|max:255',
        ]);

        //should return/reject if validation failed

        // create ticket
        $staff = Category::create([
            'user_id' => $request->user()->id,   // logged in user, i think this one can do like global interceptor, or like uhh i forgot, something like that yeah
            'category' => $validated['category'],
        ]);

        // send response
        return response()->json([
            'message' => 'Category Created Successfully',
            'Staff' => $staff
        ], 201);
    }
}

