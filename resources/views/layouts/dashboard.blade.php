<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Painel de Controle - {{ config('app.name', 'Laravel') }}</title>
    
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A==" crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>
<body class="bg-gray-100 font-sans antialiased">
    <div class="flex h-screen bg-gray-100">
        <aside class="w-64 bg-gray-800 text-gray-100 p-4 flex flex-col">
            <div class="mb-8">
                <h1 class="text-2xl font-bold">Painel</h1>
            </div>
            <nav class="flex-grow">
                <ul>
                    <li class="mb-2">
                        <a href="{{ route('admin.dashboard') }}" class="flex items-center p-2 rounded-md hover:bg-gray-700">
                            <i class="fas fa-home w-5 mr-3"></i>
                            Dashboard
                        </a>
                    </li>
                    <li class="mb-2">
                        <a href="{{ route('admin.categories.index') }}" class="flex items-center p-2 rounded-md hover:bg-gray-700">
                            <i class="fas fa-list-alt w-5 mr-3"></i>
                            Categorias
                        </a>
                    </li>
                    <li class="mb-2">
                        <a href="{{ route('admin.items.index') }}" class="flex items-center p-2 rounded-md hover:bg-gray-700">
                            <i class="fas fa-hamburger w-5 mr-3"></i>
                            Itens do Cardápio
                        </a>
                    </li>
                    {{-- NOVO LINK: Configurações --}}
                    <li class="mb-2">
                        <a href="{{ route('admin.settings.edit') }}" class="flex items-center p-2 rounded-md hover:bg-gray-700">
                            <i class="fas fa-cog w-5 mr-3"></i> {{-- Ícone de engrenagem para configurações --}}
                            Configurações
                        </a>
                    </li>
                    {{-- FIM DO NOVO LINK --}}
                </ul>
            </nav>
            <div class="mt-auto">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="w-full flex items-center p-2 rounded-md hover:bg-gray-700 text-left">
                        <i class="fas fa-sign-out-alt w-5 mr-3"></i>
                        Sair
                    </button>
                </form>
            </div>
        </aside>

        <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-200">
            <header class="bg-white shadow-sm p-4 flex items-center justify-between">
                <div>
                    <h2 class="text-xl font-semibold text-gray-800">
                        @yield('page_title', 'Bem-vindo!')
                    </h2>
                </div>
                <div>
                    <span>Olá, {{ Auth::user()->name }}</span>
                </div>
            </header>
            <div class="p-6">
                @yield('content')
            </div>
        </main>
    </div>

    {{-- AQUI VOCÊ DEVE ADICIONAR ESTA LINHA --}}
    @yield('scripts')
</body>
</html>