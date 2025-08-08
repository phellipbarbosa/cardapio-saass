@extends('layouts.dashboard')

@section('page_title', 'Editar Item: ' . $item->name)

@section('content')
<div class="max-w-md mx-auto">
    <div class="bg-white p-6 rounded-lg shadow-md">
        <h3 class="text-xl font-bold text-gray-900 mb-4">Editar Item: {{ $item->name }}</h3>
        
        {{-- Mensagem de Sucesso (Opcional, mas útil) --}}
        @if (session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4 text-sm" role="alert">
                <span class="block sm:inline">{{ session('success') }}</span>
            </div>
        @endif

        <form method="POST" action="{{ route('admin.items.update', $item) }}" enctype="multipart/form-data" class="flex flex-col space-y-4">
            @csrf
            @method('PUT')
            
            {{-- Campo de Seleção da Categoria --}}
            <div>
                <label for="category_id" class="block text-sm font-medium text-gray-700">Categoria</label>
                <select name="category_id" id="category_id" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    @foreach($categories as $categoryOption)
                        <option value="{{ $categoryOption->id }}" {{ old('category_id', $item->category_id) == $categoryOption->id ? 'selected' : '' }}>
                            {{ $categoryOption->name }}
                        </option>
                    @endforeach
                </select>
                @error('category_id') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            {{-- Campo Nome do Item --}}
            <div>
                <label for="name" class="block text-sm font-medium text-gray-700">Nome do Item</label>
                <input type="text" name="name" id="name" value="{{ old('name', $item->name) }}" placeholder="Ex: Hambúrguer Clássico" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500">
                @error('name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>
            
            {{-- Campo Descrição --}}
            <div>
                <label for="description" class="block text-sm font-medium text-gray-700">Descrição (opcional)</label>
                <textarea name="description" id="description" placeholder="Ex: Pão, carne, queijo e salada." rows="3" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500">{{ old('description', $item->description) }}</textarea>
                @error('description') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            {{-- Campo Preço --}}
            <div>
                <label for="price" class="block text-sm font-medium text-gray-700">Preço</label>
                <input type="number" name="price" id="price" step="0.01" value="{{ old('price', $item->price) }}" placeholder="Ex: 25.50" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500">
                @error('price') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>
            
            {{-- Campo Anexar Imagem --}}
            <div>
                <label for="image" class="block text-sm font-medium text-gray-700">Foto do Item (deixe em branco para manter a atual)</label>
                <input type="file" name="image" id="image" class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                @error('image') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror

                @if($item->image_path)
                    <p class="mt-2 text-sm text-gray-600">Foto atual:</p>
                    <img src="{{ Storage::url($item->image_path) }}" alt="{{ $item->name }}" class="mt-1 w-24 h-24 object-cover rounded-md">
                @endif
            </div>
            
            {{-- NOVO: Checkbox para marcar como destaque --}}
            <div>
                <div class="flex items-center">
                    <input type="checkbox" name="is_featured" id="is_featured" value="1" {{ old('is_featured', $item->is_featured) ? 'checked' : '' }} class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                    <label for="is_featured" class="ml-2 text-sm font-medium text-gray-700">Marcar como destaque</label>
                </div>
                @error('is_featured') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            {{-- BLOCO DO CHECKBOX is_active --}}
            <div>
                <div class="flex items-center">
                    <input type="hidden" name="is_active" value="0">
                    <input type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', $item->is_active) ? 'checked' : '' }} class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                    <label for="is_active" class="ml-2 text-sm font-medium text-gray-700">Ativo (Visível no Cardápio)</label>
                </div>
                @error('is_active') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="flex items-center justify-end mt-4">
                <button type="submit" class="w-full px-4 py-2 bg-blue-600 text-white font-semibold rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                    Atualizar Item
                </button>
            </div>
        </form>
    </div>
</div>
@endsection