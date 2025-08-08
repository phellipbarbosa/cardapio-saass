@extends('layouts.dashboard')

@section('page_title', 'Editar Categoria: ' . $category->name)

@section('content')
<div class="max-w-md mx-auto">
    <div class="bg-white p-6 rounded-lg shadow-md">
        <h3 class="text-xl font-bold text-gray-900 mb-4">Editar Categoria: {{ $category->name }}</h3>
        
        {{-- Mensagem de Sucesso (Opcional, mas útil) --}}
        @if (session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4 text-sm" role="alert">
                <span class="block sm:inline">{{ session('success') }}</span>
            </div>
        @endif

        <form method="POST" action="{{ route('admin.categories.update', $category) }}" enctype="multipart/form-data" class="flex flex-col space-y-4"> {{-- CORRIGIDO AQUI --}}
            @csrf
            @method('PUT') {{-- ESSENCIAL: Diz ao Laravel para tratar esta requisição como PUT --}}
            
            {{-- Campo Nome da Categoria --}}
            <div>
                <label for="name" class="block text-sm font-medium text-gray-700">Nome da Categoria</label>
                <input type="text" name="name" id="name" value="{{ old('name', $category->name) }}" placeholder="Ex: Bebidas" required
                       class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500">
                @error('name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>
            
            {{-- Campo Anexar Imagem --}}
            <div>
                <label for="image" class="block text-sm font-medium text-gray-700">Capa (deixe em branco para manter a atual)</label>
                <input type="file" name="image" id="image"
                       class="mt-1 block w-full text-sm text-gray-500
                              file:mr-4 file:py-2 file:px-4
                              file:rounded-full file:border-0
                              file:text-sm file:font-semibold
                              file:bg-blue-50 file:text-blue-700
                              hover:file:bg-blue-100">
                @error('image') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror

                @if($category->image_path)
                    <p class="mt-2 text-sm text-gray-600">Capa atual:</p>
                    <img src="{{ Storage::url($category->image_path) }}" alt="{{ $category->name }}" class="mt-1 w-24 h-24 object-cover rounded-md">
                @endif
            </div>
            
            {{-- Correção para o campo is_active --}}
            <div>
                <input type="hidden" name="is_active" value="0"> {{-- Campo oculto para enviar '0' se o checkbox não for marcado --}}
                <input type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', $category->is_active) ? 'checked' : '' }} class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                <label for="is_active" class="ml-2 text-sm font-medium text-gray-700">Ativa (Visível no Cardápio)</label>
                @error('is_active') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <button type="submit" class="w-full px-4 py-2 bg-blue-600 text-white font-semibold rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                Atualizar Categoria
            </button>
        </form>
    </div>
</div>
@endsection