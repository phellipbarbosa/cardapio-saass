@extends('layouts.dashboard')

@section('page_title', 'Categorias do Cardápio')

@section('content')
    <div class="flex items-center justify-between mb-8">
        <h2 class="text-2xl font-bold text-gray-800">Minhas Categorias</h2>
        <a href="{{ route('admin.categories.create') }}" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700"> {{-- CORRIGIDO --}}
            Adicionar Nova Categoria
        </a>
    </div>

    @if (session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
            <span class="block sm:inline">{{ session('success') }}</span>
        </div>
    @endif

    @if($categories->isEmpty())
        <p class="text-gray-500">Nenhuma categoria cadastrada.</p>
    @else
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($categories as $category)
                <div class="bg-white rounded-xl shadow-md overflow-hidden p-6 hover:shadow-lg transition-shadow duration-300">
                    <div class="w-full h-40 bg-gray-200 rounded-lg mb-4 flex items-center justify-center text-gray-500">
                        @if($category->image_path)
                            <img src="{{ Storage::url($category->image_path) }}" alt="{{ $category->name }}" class="w-full h-full object-cover rounded-lg">
                        @else
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l-4.586-4.586a2 2 0 00-2.828 0L4 12m10 4l-4.586-4.586a2 2 0 01-2.828 0L20 16m-2-2l-4.586-4.586a2 2 0 00-2.828 0L4 12m10 4l-4.586-4.586a2 2 0 01-2.828 0L20 8m-4-4l4.586-4.586a2 2 0 00-2.828 0L4 8" />
                            </svg>
                        @endif
                    </div>
            
                    <div class="flex items-center justify-between">
                        <h3 class="text-2xl font-bold text-gray-900">{{ $category->name }}</h3>
                        @if($category->is_active)
                            <span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">Ativa</span>
                        @else
                            <span class="px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">Inativa</span>
                        @endif
                        <div class="flex items-center space-x-2">
                            <a href="{{ route('admin.categories.edit', $category) }}" class="px-4 py-2 text-sm font-semibold text-blue-600 bg-blue-100 rounded-full hover:bg-blue-200 transition-colors duration-200"> {{-- CORRIGIDO --}}
                                Editar
                            </a>
                            <form method="POST" action="{{ route('admin.categories.destroy', $category) }}" onsubmit="return confirm('Tem certeza que deseja deletar esta categoria? Todos os itens associados também serão deletados.');"> {{-- CORRIGIDO --}}
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="px-4 py-2 text-sm font-semibold text-red-600 bg-red-100 rounded-full hover:bg-red-200 transition-colors duration-200">
                                    Deletar
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
@endsection