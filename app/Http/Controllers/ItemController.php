<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Item;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class ItemController extends Controller
{
    /**
     * Exibe a lista de todos os itens do usuário autenticado.
     */
    public function index()
    {
        $items = Auth::user()->items()->paginate(10);
        $categories = Auth::user()->categories;

        return view('items.index', compact('items', 'categories'));
    }

    /**
     * Exibe o formulário para criar um novo item.
     */
    public function create()
    {
        $categories = Auth::user()->categories;
        return view('items.create', compact('categories'));
    }

    /**
     * Salva um novo item no banco de dados.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'price' => ['required', 'numeric'],
            'image' => ['nullable', 'image', 'max:2048'], // 2MB
            'category_id' => ['required', 'exists:categories,id', Rule::in(Auth::user()->categories->pluck('id'))],
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('items', 'public');
        }

        Item::create([
            'name' => $validatedData['name'],
            'description' => $validatedData['description'],
            'price' => $validatedData['price'],
            'image_path' => $imagePath,
            'category_id' => $validatedData['category_id'],
            'is_active' => $request->has('is_active'), // Verifica o checkbox 'Ativo'
            'is_featured' => $request->has('is_featured'), // Adicionado para verificar o checkbox 'Destaque'
        ]);

        return redirect()->route('admin.items.index')->with('success', 'Item criado com sucesso!');
    }

    /**
     * Exibe o formulário para editar um item.
     */
    public function edit(Item $item)
    {
        // Garante que o item pertence ao usuário autenticado
        if ($item->category->user_id !== Auth::id()) {
            abort(403, 'Acesso não autorizado.');
        }

        $categories = Auth::user()->categories;
        return view('items.edit', compact('item', 'categories'));
    }

    /**
     * Atualiza um item no banco de dados.
     */
    public function update(Request $request, Item $item)
    {
        // Garante que o item pertence ao usuário autenticado
        if ($item->category->user_id !== Auth::id()) {
            abort(403, 'Acesso não autorizado.');
        }

        $validatedData = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'price' => ['required', 'numeric'],
            'image' => ['nullable', 'image', 'max:2048'],
            'category_id' => ['required', 'exists:categories,id', Rule::in(Auth::user()->categories->pluck('id'))],
        ]);

        if ($request->hasFile('image')) {
            if ($item->image_path) {
                Storage::disk('public')->delete($item->image_path);
            }
            $validatedData['image_path'] = $request->file('image')->store('items', 'public');
        }

        // Salva o status do destaque e ativo
        $validatedData['is_active'] = $request->has('is_active');
        $validatedData['is_featured'] = $request->has('is_featured');
        
        $item->update($validatedData);

        return redirect()->route('admin.items.index')->with('success', 'Item atualizado com sucesso!');
    }

    /**
     * Remove um item do banco de dados.
     */
    public function destroy(Item $item)
    {
        if ($item->category->user_id !== Auth::id()) {
            abort(403, 'Acesso não autorizado.');
        }

        if ($item->image_path) {
            Storage::disk('public')->delete($item->image_path);
        }

        $item->delete();
        return back()->with('success', 'Item excluído com sucesso.');
    }

    // Método para exibir a lista de itens de uma categoria específica
    public function indexByCategory(Category $category)
    {
        if ($category->user_id !== Auth::id()) {
            abort(403, 'Acesso não autorizado.');
        }

        $items = $category->items()->paginate(10);
        $categories = Auth::user()->categories;

        return view('items.index', compact('items', 'categories'));
    }
}