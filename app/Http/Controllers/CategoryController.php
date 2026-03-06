<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Inertia\Inertia;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Services\HistoryLogService;

class CategoryController extends Controller
{
    public function index()
    {   
        $Category = Category::orderBy('category', 'asc')->get();
        return response()->json(['category' => $Category]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'category' => 'required|string|max:255',
        ]);

        try {

            DB::beginTransaction();

            $category = Category::create([
                'user_id' => $request->user()->id,
                'category' => $validated['category'],
            ]);

            HistoryLogService::log(
                $request->user()->id,
                'Create Category ' . $category->category,
                'Category',
                true,
                $request->ip()
            );

            DB::commit();

            return response()->json([
                'message' => 'Category Created Successfully',
                'category' => $category
            ], 201);

        } catch (\Exception $e) {

            DB::rollBack();

            return response()->json([
                'message' => 'Failed to create category',
                'error' => $e->getMessage() // remove in production
            ], 500);
        }
    }

    public function destroy(Request $request, Category $category)
    {
        try {

            DB::beginTransaction();

            $category->delete(); // soft delete (if SoftDeletes enabled)

            HistoryLogService::log(
                $request->user()->id,
                'Delete Category ' . $category->category,
                'Category',
                true,
                request()->ip()
            );

            DB::commit();

            return response()->json([
                'message' => 'Category deleted successfully'
            ], 200);

        } catch (\Exception $e) {

            DB::rollBack();

            return response()->json([
                'message' => 'Failed to delete category',
                'error' => $e->getMessage() // remove in production
            ], 500);
        }
    }
}

