<?php

namespace App\Http\Controllers\Admin;

use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CategoryController extends Controller
{
    /**
     * Store a newly created category via AJAX.
     */
    public function storeAjax(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100|unique:categories,name',
            'description' => 'nullable|string|max:500',
        ]);

        try {
            $category = Category::create([
                'name' => $validated['name'],
                'slug' => \Str::slug($validated['name']),
                'description' => $validated['description'] ?? null,
                'is_active' => true,
            ]);

            return response()->json([
                'status' => 'success',
                'message' => 'Kategori berhasil ditambahkan',
                'category' => $category,
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal menambahkan kategori: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Show the standalone create category form.
     */
    public function create()
    {
        return view('admin.categories.create');
    }

    /**
     * Store a newly created category from the standalone form.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100|unique:categories,name',
            'description' => 'nullable|string|max:500',
        ]);

        try {
            $category = Category::create([
                'name' => $validated['name'],
                'slug' => \Str::slug($validated['name']),
                'description' => $validated['description'] ?? null,
                'is_active' => true,
            ]);

            return redirect()->route('admin.categories.create')->with('success', 'Kategori berhasil ditambahkan.');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->withErrors(['error' => 'Gagal menambahkan kategori: ' . $e->getMessage()]);
        }
    }
}
