<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::withCount('complaints')->orderBy('name')->get();
        return view('master.categories.index', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:100', 'unique:categories,name'],
            'icon' => ['nullable', 'string', 'max:50'],
            'description' => ['nullable', 'string', 'max:255'],
        ], [
            'name.required' => 'Nama kategori wajib diisi.',
            'name.unique' => 'Kategori ini sudah ada.',
        ]);

        Category::create([
            'name' => $validated['name'],
            'icon' => $validated['icon'] ?? 'bi-box-seam',
            'description' => $validated['description'] ?? null,
        ]);

        return back()->with('success', 'Kategori sarana baru berhasil ditambahkan.');
    }

    public function update(Request $request, $id)
    {
        $category = Category::findOrFail($id);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:100', 'unique:categories,name,' . $category->id],
            'icon' => ['nullable', 'string', 'max:50'],
            'description' => ['nullable', 'string', 'max:255'],
        ]);

        $category->update([
            'name' => $validated['name'],
            'icon' => $validated['icon'] ?? $category->icon,
            'description' => $validated['description'] ?? null,
        ]);

        return back()->with('success', 'Data kategori berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $category = Category::withCount('complaints')->findOrFail($id);

        if ($category->complaints_count > 0) {
            return back()->with('error', "Kategori '{$category->name}' tidak dapat dihapus karena memiliki {$category->complaints_count} data pengaduan terkait.");
        }

        $category->delete();
        return back()->with('success', 'Kategori sarana berhasil dihapus.');
    }
}
