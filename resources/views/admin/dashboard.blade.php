@extends('layouts.dashboard')

@section('page_title', 'Visão Geral')

@section('content')
    {{-- Bloco de aviso sobre o período de teste --}}
    @if (Auth::user()->trial_ends_at)
        @php
            $trialEndsAt = \Carbon\Carbon::parse(Auth::user()->trial_ends_at);
            $now = \Carbon\Carbon::now();
            $diffInDays = $now->diffInDays($trialEndsAt, false);
        @endphp
        <div class="bg-yellow-100 border-l-4 border-yellow-500 text-yellow-700 p-4 mb-4" role="alert">
            @if ($diffInDays > 0)
                <p class="font-bold">Aviso:</p>
                <p>Seu período de teste expira em {{ $diffInDays }} dias. Entre em contato para ativar seu plano.</p>
            @elseif ($diffInDays == 0)
                <p class="font-bold">Aviso:</p>
                <p>Seu período de teste expira hoje! Entre em contato para ativar seu plano e não perder o acesso.</p>
            @else
                <p class="font-bold">Aviso:</p>
                <p>Seu período de teste expirou. Entre em contato para reativar seu plano.</p>
            @endif
        </div>
    @endif
    
    {{-- Mensagem de Sucesso de Registro --}}
    @if (session('registration_success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
            <span class="block sm:inline">{{ session('registration_success') }}</span>
        </div>
    @endif

    <h3 class="text-2xl font-bold">Bem-vindo ao Painel de Controle!</h3>
    <p class="mt-4 text-gray-600">Use o menu lateral para gerenciar suas categorias e itens.</p>

    {{-- Bloco para exibir o link do cardápio público --}}
    <div class="mt-8 bg-white p-6 rounded-lg shadow-md">
        <h4 class="text-xl font-semibold text-gray-800 mb-4">Seu Cardápio Online</h4>
        @if(Auth::user()->slug)
            <p class="text-gray-700 mb-2">Seu cardápio está disponível em:</p>
            <div class="flex items-center space-x-2 bg-gray-100 p-3 rounded-md border border-gray-200">
                <a href="{{ route('public.menu', ['slug' => Auth::user()->slug]) }}" target="_blank" class="text-blue-600 hover:underline font-mono text-sm break-all">
                    {{ route('public.menu', ['slug' => Auth::user()->slug]) }}
                </a>
                <button onclick="copyToClipboard('{{ route('public.menu', ['slug' => Auth::user()->slug]) }}')" class="ml-auto px-3 py-1 bg-blue-500 text-white rounded-md text-xs hover:bg-blue-600">
                    Copiar Link
                </button>
            </div>
            <p class="text-gray-500 text-sm mt-2">Compartilhe este link com seus clientes!</p>
        @else
            <p class="text-gray-700">Para ter seu cardápio online, por favor, defina um <span class="font-semibold">Slug do Cardápio</span> em seu <a href="{{ route('profile.edit') }}" class="text-blue-600 hover:underline">Perfil</a>.</p>
        @endif
    </div>

    {{-- Script para copiar para a área de transferência --}}
    <script>
        function copyToClipboard(text) {
            navigator.clipboard.writeText(text).then(function() {
                alert('Link copiado para a área de transferência!');
            }, function(err) {
                alert('Erro ao copiar o link: ' + err);
            });
        }
    </script>
@endsection