@extends('layouts.dashboard')

@section('page_title', 'Adicionar Novo Item')

@section('content')
<div class="max-w-md mx-auto">
    <div class="bg-white p-6 rounded-lg shadow-md">
        <h3 class="text-xl font-bold text-gray-900 mb-4">Adicionar Novo Item</h3>
        
        @if (session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4 text-sm" role="alert">
                <span class="block sm:inline">{{ session('success') }}</span>
            </div>
        @endif

        <form method="POST" action="{{ route('admin.items.store') }}" enctype="multipart/form-data" class="flex flex-col space-y-4">
            @csrf
            
            <div>
                <label for="category_id" class="block text-sm font-medium text-gray-700">Categoria</label>
                <select name="category_id" id="category_id" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                    @endforeach
                </select>
                @error('category_id') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="name" class="block text-sm font-medium text-gray-700">Nome do Item</label>
                <input type="text" name="name" id="name" placeholder="Ex: Hambúrguer Clássico" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500">
                @error('name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>
            
            <div>
                <label for="description" class="block text-sm font-medium text-gray-700">Descrição (opcional)</label>
                <textarea name="description" id="description" placeholder="Ex: Pão, carne, queijo e salada." rows="3" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500"></textarea>
                @error('description') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="price" class="block text-sm font-medium text-gray-700">Preço</label>
                <input type="number" name="price" id="price" step="0.01" placeholder="Ex: 25.50" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500">
                @error('price') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>
            
            <div>
                <label for="image" class="block text-sm font-medium text-gray-700">Foto do Item</label>
                <input type="file" name="image" id="image" class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                @error('image') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>
            
            {{-- NOVO: Checkbox para marcar como destaque --}}
            <div>
                <div class="flex items-center">
                    <input type="checkbox" name="is_featured" id="is_featured" value="1" class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                    <label for="is_featured" class="ml-2 text-sm font-medium text-gray-700">Marcar como destaque</label>
                </div>
                @error('is_featured') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            {{-- BLOCO DO CHECKBOX is_active --}}
            <div>
                <input type="hidden" name="is_active" value="0">
                <input type="checkbox" name="is_active" id="is_active" value="1" checked class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                <label for="is_active" class="ml-2 text-sm font-medium text-gray-700">Ativo (Visível no Cardápio)</label>
                @error('is_active') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <button type="submit" class="w-full px-4 py-2 bg-blue-600 text-white font-semibold rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                Adicionar Item
            </button>
        </form>
    </div>
</div>
@endsection