<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Auth::user()->categories;
        return view('categories.index', compact('categories'));
    }

    public function create()
    {
        return view('categories.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'image' => 'nullable|image|max:2048',
            'is_active' => 'boolean', // Valida se é um booleano
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('public/categories_images');
        }

        Category::create([
            'name' => $request->name,
            'user_id' => Auth::id(),
            'image_path' => $imagePath,
            'is_active' => $request->boolean('is_active'), // <--- CORREÇÃO AQUI: Usar $request->boolean()
        ]);

        return redirect()->route('admin.categories.index')->with('success', 'Categoria adicionada com sucesso!');
    }
    
    public function edit(Category $category)
    {
        return view('categories.edit', compact('category'));
    }

    public function update(Request $request, Category $category)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'image' => 'nullable|image|max:2048',
            'is_active' => 'boolean', // Valida se é um booleano
        ]);

        if ($request->hasFile('image')) {
            if ($category->image_path) {
                Storage::delete($category->image_path);
            }
            $imagePath = $request->file('image')->store('public/categories_images');
            $category->image_path = $imagePath;
        }

        $category->name = $request->name;
        $category->is_active = $request->boolean('is_active'); // Já está correto aqui
        $category->save();

        return redirect()->route('admin.categories.index')->with('success', 'Categoria atualizada com sucesso!');
    }

    public function destroy(Category $category)
    {
        if ($category->image_path) {
            Storage::delete($category->image_path);
        }
        $category->delete();
        return redirect()->route('admin.categories.index')->with('success', 'Categoria excluída com sucesso!');
    }
}