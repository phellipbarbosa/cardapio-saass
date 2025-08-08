@extends('layouts.dashboard')

@section('page_title')
    @if(isset($category))
        Itens da Categoria: {{ $category->name }}
    @else
        Todos os Itens do Cardápio
    @endif
@endsection

@section('content')
<div class="container mx-auto p-6">

    {{-- Seção de título e botão de adicionar --}}
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-bold text-gray-800">
            @if(isset($category))
                Itens da Categoria: {{ $category->name }}
            @else
                Todos os Itens do Cardápio
            @endif
        </h1>
        <a href="{{ route('admin.items.create') }}" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700"> {{-- CORRIGIDO AQUI --}}
            Adicionar Novo Item
        </a>
    </div>

    {{-- Formulário de Filtro --}}
    <div class="bg-white p-4 rounded-lg shadow-md mb-8">
        <h3 class="text-lg font-semibold text-gray-700 mb-2">Filtrar por Categoria</h3>
        <form action="{{ route('admin.items.index') }}" method="GET" class="flex items-center space-x-4"> {{-- CORRIGIDO AQUI --}}
            <select name="category_id" onchange="this.form.submit()" class="border-gray-300 rounded-md shadow-sm">
                <option value="">Todas as Categorias</option>
                @foreach ($categories as $cat)
                    <option value="{{ $cat->id }}" {{ (isset($category) && $cat->id == $category->id) || (request('category_id') == $cat->id) ? 'selected' : '' }}>
                        {{ $cat->name }}
                    </option>
                @endforeach
            </select>
        </form>
    </div>

    {{-- Mensagem de Sucesso --}}
    @if (session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
            <span class="block sm:inline">{{ session('success') }}</span>
        </div>
    @endif

    {{-- Lista de Itens --}}
    @if($items->isEmpty())
        <p class="text-gray-500">Nenhum item cadastrado nesta categoria.</p>
    @else
        <div class="space-y-4">
            @foreach($items as $item)
                <div class="flex items-center justify-between p-4 bg-white rounded-lg shadow-sm">
                    {{-- Bloco de Informações do Item --}}
                    <div class="flex items-center space-x-4">
                        @if($item->image_path)
                            <img src="{{ Storage::url($item->image_path) }}" alt="{{ $item->name }}" class="w-16 h-16 object-cover rounded-md">
                        @endif
                        <div>
                            <h3 class="font-semibold text-lg text-gray-800 flex items-center space-x-2">
                                <span>{{ $item->name }}</span>
                                {{-- Indicador de status --}}
                                @if($item->is_active)
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">Ativo</span>
                                @else
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">Inativo</span>
                                @endif
                            </h3>
                            <p class="text-gray-600 text-sm">R$ {{ number_format($item->price, 2, ',', '.') }}</p>
                            @if($item->description)
                                <p class="text-gray-500 text-xs mt-1">{{ $item->description }}</p>
                            @endif
                            <p class="text-gray-500 text-xs mt-1">Categoria: {{ $item->category->name ?? 'N/A' }}</p>
                        </div>
                    </div>
    
                    {{-- Bloco de Botões --}}
                    <div class="flex items-center space-x-2">
                        {{-- Botão EDITAR --}}
                        <a href="{{ route('admin.items.edit', $item) }}" class="font-semibold text-sm text-blue-500 hover:text-blue-700
                                   border border-blue-500 rounded-md py-1 px-4 hover:bg-blue-50"> {{-- CORRIGIDO AQUI --}}
                            &nbsp;Editar
                        </a>
    
                        {{-- Botão DELETAR --}}
                        <form method="POST" action="{{ route('admin.items.destroy', $item) }}" onsubmit="return confirm('Tem certeza que deseja deletar este item?');"> {{-- CORRIGIDO AQUI --}}
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="font-semibold text-sm text-red-500 hover:text-red-700
                                         border border-red-500 rounded-md py-1 px-4 hover:bg-red-50">
                                &nbsp;Deletar
                            </button>
                        </form>
                    </div>
                </div>
            @endforeach
        </div>

        {{-- Links de Paginação --}}
        <div class="mt-8">
            {{ $items->links() }}
        </div>
    @endif
</div>
@endsection