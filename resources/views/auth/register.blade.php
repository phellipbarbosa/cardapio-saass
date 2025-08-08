<x-guest-layout>
    <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-gray-100">
        <div class="w-full sm:max-w-md mt-6 px-6 py-4 bg-white shadow-md overflow-hidden sm:rounded-lg">
            
            <div class="flex justify-center mb-4">
                <a href="/">
                    <img src="https://placehold.co/100x100/ffffff/000000?text=Logo" alt="Logo" class="block h-10 w-auto fill-current text-gray-800">
                </a>
            </div>

            @if ($errors->any())
                <div class="mb-4">
                    <div class="font-medium text-red-600">
                        {{ __('Ocorreram alguns erros no seu registro:') }}
                    </div>

                    <ul class="mt-3 list-disc list-inside text-sm text-red-600">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('register') }}">
                @csrf

                <div>
                    <label for="name" class="block font-medium text-sm text-gray-700">Seu Nome</label>
                    <input id="name" class="block mt-1 w-full rounded-md shadow-sm border-gray-300" type="text" name="name" value="{{ old('name') }}" required autofocus autocomplete="name" />
                </div>

                <div class="mt-4">
                    <label for="restaurant_name" class="block font-medium text-sm text-gray-700">Nome da Empresa</label>
                    <input id="restaurant_name" class="block mt-1 w-full rounded-md shadow-sm border-gray-300" type="text" name="restaurant_name" value="{{ old('restaurant_name') }}" required autocomplete="restaurant_name" />
                </div>

                <div class="mt-4">
                    <label for="email" class="block font-medium text-sm text-gray-700">Email</label>
                    <input id="email" class="block mt-1 w-full rounded-md shadow-sm border-gray-300" type="email" name="email" value="{{ old('email') }}" required autocomplete="username" />
                </div>

                <div class="mt-4">
                    <label for="password" class="block font-medium text-sm text-gray-700">Senha</label>
                    <input id="password" class="block mt-1 w-full rounded-md shadow-sm border-gray-300" type="password" name="password" required autocomplete="new-password" />
                </div>

                <div class="mt-4">
                    <label for="password_confirmation" class="block font-medium text-sm text-gray-700">Confirmar Senha</label>
                    <input id="password_confirmation" class="block mt-1 w-full rounded-md shadow-sm border-gray-300" type="password" name="password_confirmation" required autocomplete="new-password" />
                </div>

                <div class="mt-4">
                    <label for="whatsapp_number" class="block font-medium text-sm text-gray-700">Número do WhatsApp</label>
                    <input id="whatsapp_number" class="block mt-1 w-full rounded-md shadow-sm border-gray-300" type="text" name="whatsapp_number" value="{{ old('whatsapp_number') }}" required placeholder="Ex: 5511999998888" />
                </div>

                <div class="mt-4">
                    <label for="opening_time" class="block font-medium text-sm text-gray-700">Horário de Abertura</label>
                    <input id="opening_time" class="block mt-1 w-full rounded-md shadow-sm border-gray-300" type="time" name="opening_time" value="{{ old('opening_time') }}" required />
                </div>

                <div class="mt-4">
                    <label for="closing_time" class="block font-medium text-sm text-gray-700">Horário de Fechamento</label>
                    <input id="closing_time" class="block mt-1 w-full rounded-md shadow-sm border-gray-300" type="time" name="closing_time" value="{{ old('closing_time') }}" required />
                </div>

                <div class="flex items-center justify-end mt-4">
                    <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" href="{{ route('login') }}">
                        {{ __('Já tem uma conta?') }}
                    </a>

                    <button type="submit" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:ring ring-gray-300 disabled:opacity-25 transition ml-4">
                        {{ __('Registrar') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-guest-layout>