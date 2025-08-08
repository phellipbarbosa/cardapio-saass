@extends('layouts.dashboard')

@section('page_title', 'Adicionar Categoria')

@section('content')
<div class="max-w-md mx-auto">
    <div class="bg-white p-6 rounded-lg shadow-md">
        <h3 class="text-xl font-bold text-gray-900 mb-4">Adicionar Nova Categoria</h3>
        <form method="POST" action="{{ route('admin.categories.store') }}" enctype="multipart/form-data" class="flex flex-col space-y-4">
            @csrf
            <div>
                <label for="name" class="block text-sm font-medium text-gray-700">Nome da Categoria</label>
                <input type="text" name="name" id="name" placeholder="Ex: Bebidas" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500">
            </div>
            <div>
                <label for="image" class="block text-sm font-medium text-gray-700">Capa (opcional)</label>
                <input type="file" name="image" id="image" class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                @error('image') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>
            
            {{-- BLOCO DO CHECKBOX is_active --}}
            <div>
                <input type="hidden" name="is_active" value="0"> {{-- CAMPO OCULTO: Garante que '0' seja enviado se o checkbox estiver desmarcado --}}
                <input type="checkbox" name="is_active" id="is_active" value="1" checked class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50"> {{-- CHECKBOX: Envia '1' se marcado --}}
                <label for="is_active" class="ml-2 text-sm font-medium text-gray-700">Ativa (Visível no Cardápio)</label>
                @error('is_active') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <button type="submit" class="w-full px-4 py-2 bg-blue-600 text-white font-semibold rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                Adicionar Categoria
            </button>
        </form>
    </div>
</div>
@endsection