<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $user->name }} - Cardápio Online</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        /* Estilos personalizados para a barra de rolagem e outros detalhes */
        body {
            scroll-behavior: smooth;
            min-height: 100vh;
            padding-bottom: 5rem;
        }
        .category-nav::-webkit-scrollbar { height: 6px; }
        .category-nav::-webkit-scrollbar-track { background: #f1f1f1; border-radius: 10px; }
        .category-nav::-webkit-scrollbar-thumb { background: #888; border-radius: 10px; }
        .category-nav::-webkit-scrollbar-thumb:hover { background: #555; }
        .banner-bg { background-image: url('https://www.kcms.com.br/blog/wp-content/uploads/2017/03/lanchonete-competitiva.jpg'); background-size: cover; background-position: center; }
        .cart-float-button {
            position: fixed; bottom: 0; left: 0; right: 0; z-index: 100;
            box-shadow: 0 -4px 6px -1px rgba(0, 0, 0, 0.1), 0 -2px 4px -1px rgba(0, 0, 0, 0.06);
            border-radius: 0; padding: 1rem; display: flex; align-items: center; justify-content: space-between;
            text-decoration: none; color: #ffffff; font-size: 1.125rem; font-weight: 600;
        }
        .cart-modal, .checkout-modal, .message-modal {
            position: fixed; top: 0; left: 0; width: 100%; height: 100%; background-color: rgba(0, 0, 0, 0.75);
            display: flex; justify-content: center; align-items: center; z-index: 200;
        }
        .cart-modal.hidden, .checkout-modal.hidden, .message-modal.hidden { display: none !important; }
        .cart-modal-content, .checkout-modal-content, .message-modal-content {
            background-color: #ffffff; border-radius: 0.75rem; padding: 1.5rem; width: 90%; max-width: 500px;
            max-height: 90vh; overflow-y: auto; position: relative;
        }
        .btn-add-to-cart {
            background-color: rgba(46, 98, 241, 0.75); color: #ffffff; padding: 0.75rem 1rem; border-radius: 0.375rem;
            font-size: 0.875rem; font-weight: 600; transition: background-color 0.2s ease-in-out;
            flex-grow: 1;
        }
        .btn-add-to-cart:hover { background-color: rgba(17, 76, 236, 0.9); }
        .btn-cart-float-blue { background-color: rgba(12, 64, 209, 0.75); }
        .btn-cart-float-blue:hover { background-color: rgba(46, 98, 241, 0.9); }
    </style>
</head>
<body class="bg-gray-50 text-gray-800 font-sans" 
      data-whatsapp-number="{{ $whatsappNumber ?? '' }}"
      data-opening-time="{{ $openingTime ?? '08:00' }}" 
      data-closing-time="{{ $closingTime ?? '18:00' }}">

    {{-- Barra Superior --}}
    <header class="bg-blue-600 text-white p-4 flex items-center justify-between">
        <div class="flex items-center">
            <img src="https://placehold.co/40x40/ffffff/000000?text=Logo" alt="Logo" class="h-10 w-10 rounded-full mr-3">
            <h1 class="text-xl font-bold">{{ $user->name }}</h1>
        </div>
        <div class="relative flex items-center">
            {{-- Search Bar - Inicialmente oculta --}}
            <div id="search-container" class="absolute right-0 top-0 flex items-center space-x-2 bg-white rounded-full px-4 py-2 text-gray-800 transition-all duration-300 transform scale-x-0 origin-right">
                <input id="search-input" type="text" placeholder="Buscar no cardápio..." class="focus:outline-none w-48 text-sm">
                <button id="search-close-button" class="text-gray-500 hover:text-gray-800">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            {{-- Botão para abrir a busca --}}
            <button id="search-open-button" class="ml-4 text-white hover:text-gray-200 z-10">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
            </button>
            {{-- Botão de compartilhamento --}}
            <button class="ml-4 text-white hover:text-gray-200">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 110-2.684 3 3 0 010 2.684z" />
                </svg>
            </button>
        </div>
    </header>

    {{-- Informações do Restaurante --}}
    <section class="bg-gray-100 p-4 text-sm text-gray-700 flex justify-between items-center">
        <div>
            <span id="store-status" class="font-semibold"></span>
            <span class="ml-4">Pedido mín. R$ 15,00</span>
        </div>
        <div>
            <a href="#" class="text-blue-600 hover:underline">Perfil da loja</a>
        </div>
    </section>

    {{-- Navegação de Categorias --}}
    <nav id="category-nav" class="sticky top-0 bg-white shadow-md z-40 py-3 overflow-x-auto whitespace-nowrap">
        <div class="container mx-auto px-4 flex space-x-4">
            @foreach($categories as $category)
                @if(!$category->items->isEmpty())
                    <a href="#category-{{ $category->id }}" class="px-4 py-2 rounded-full text-sm font-semibold text-gray-700 bg-gray-100 hover:bg-blue-100 hover:text-blue-700 transition-colors duration-200">
                        {{ $category->name }}
                    </a>
                @endif
            @endforeach
        </div>
    </nav>

    {{-- Conteúdo do Cardápio --}}
    <main class="container mx-auto px-4 py-8">
        @if($categories->isEmpty())
            <p class="text-center text-gray-600 text-lg">Este cardápio está vazio ou indisponível no momento.</p>
        @else
            {{-- Seção de itens em destaque (horizontal) --}}
            @if(!$featuredItems->isEmpty())
                <section class="mb-10 pt-4">
                    <h2 class="text-3xl font-bold text-gray-900 mb-6">Destaques</h2>
                    <div class="flex space-x-4 overflow-x-auto pb-4 scroll-smooth">
                        @foreach($featuredItems as $item)
                            <div class="flex-shrink-0 w-48 bg-white rounded-lg shadow-sm overflow-hidden p-3 flex flex-col">
                                <img src="{{ Storage::url($item->image_path ?? 'https://placehold.co/96x96/e2e8f0/64748b?text=Item') }}" alt="{{ $item->name }}" class="w-full h-24 object-cover rounded-md mb-2">
                                <h3 class="item-name text-sm font-semibold text-gray-900 truncate">{{ $item->name }}</h3>
                                @if($item->description)
                                    <p class="item-description text-gray-600 text-xs truncate">{{ $item->description }}</p>
                                @endif
                                <p class="text-blue-600 text-base font-bold mt-auto">R$ {{ number_format($item->price, 2, ',', '.') }}</p>
                                <div class="flex items-center space-x-2 mt-2">
                                    <div class="flex items-center space-x-2">
                                        <button onclick="updateQuantity({{ $item->id }}, -1)" class="px-2 py-1 bg-gray-200 text-gray-800 rounded-md hover:bg-gray-300 transition-colors duration-200">-</button>
                                        <span id="quantity-{{ $item->id }}" class="font-semibold text-base">0</span>
                                        <button onclick="updateQuantity({{ $item->id }}, 1)" class="px-2 py-1 bg-gray-200 text-gray-800 rounded-md hover:bg-gray-300 transition-colors duration-200">+</button>
                                    </div>
                                    <button onclick="addToCart({{ $item->id }}, '{{ $item->name }}', {{ $item->price }}, '{{ Storage::url($item->image_path) }}')" class="btn-add-to-cart ml-auto">
                                        Adicionar
                                    </button>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </section>
            @endif

            {{-- Seção principal de itens do cardápio --}}
            @foreach($categories as $category)
                @if(!$category->items->isEmpty())
                    <section id="category-{{ $category->id }}" class="mb-10 pt-4">
                        <h2 class="text-3xl font-bold text-gray-900 mb-6 category-header">{{ $category->name }}</h2>
                        @if($category->items->isEmpty())
                            <p class="text-gray-600 text-sm">Nenhum item disponível nesta categoria no momento.</p>
                        @else
                            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-2 lg:grid-cols-3 gap-4">
                                @foreach($category->items as $item)
                                    <div class="menu-item bg-white rounded-lg shadow-sm overflow-hidden p-4 flex items-center">
                                        <img src="{{ Storage::url($item->image_path ?? 'https://placehold.co/96x96/e2e8f0/64748b?text=Item') }}" alt="{{ $item->name }}" class="w-24 h-24 object-cover rounded-md flex-shrink-0 mr-4">
                                        <div class="flex-grow flex flex-col justify-between">
                                            <div>
                                                <h3 class="item-name text-lg font-semibold text-gray-900">{{ $item->name }}</h3>
                                                @if($item->description)
                                                    <p class="item-description text-gray-600 text-sm mb-1">{{ $item->description }}</p>
                                                @endif
                                                <p class="text-blue-600 text-base font-bold">R$ {{ number_format($item->price, 2, ',', '.') }}</p>
                                            </div>
                                            <div class="flex items-center space-x-2 mt-4">
                                                <div class="flex items-center space-x-2">
                                                    <button onclick="updateQuantity({{ $item->id }}, -1)" class="px-2 py-1 bg-gray-200 text-gray-800 rounded-md hover:bg-gray-300 transition-colors duration-200">-</button>
                                                    <span id="quantity-{{ $item->id }}" class="font-semibold text-base">0</span>
                                                    <button onclick="updateQuantity({{ $item->id }}, 1)" class="px-2 py-1 bg-gray-200 text-gray-800 rounded-md hover:bg-gray-300 transition-colors duration-200">+</button>
                                                </div>
                                                <button onclick="addToCart({{ $item->id }}, '{{ $item->name }}', {{ $item->price }}, '{{ Storage::url($item->image_path) }}')" class="btn-add-to-cart ml-auto">
                                                    Adicionar
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </section>
                @endif
            @endforeach
        @endif
    </main>

    {{-- Botão Flutuante do Carrinho --}}
    <button id="cart-button" class="cart-float-button btn-cart-float-blue p-4 flex items-center justify-center space-x-2 text-white hover:bg-blue-700 transition-colors duration-200" onclick="toggleCartModal()">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 0a2 2 0 100 4 2 2 0 000-4z" />
        </svg>
        <span class="text-lg font-semibold">Ir para Carrinho</span>
        <span id="cart-count" class="flex items-center justify-center rounded-full bg-red-600 w-6 h-6 text-xs text-white">0</span>
    </button>

    {{-- Modal do Carrinho --}}
    <div id="cart-modal" class="cart-modal hidden">
        <div class="cart-modal-content">
            <button class="absolute top-4 right-4 text-gray-500 hover:text-gray-800 text-2xl font-bold" onclick="toggleCartModal()">&times;</button>
            <h2 class="text-2xl font-bold text-gray-900 mb-4">Seu Pedido</h2>
            <div id="cart-items" class="mb-4">
                {{-- Itens do carrinho serão injetados aqui pelo JS --}}
            </div>
            <div class="border-t border-gray-200 pt-4 flex justify-between items-center">
                <span class="text-xl font-bold">Total:</span>
                <span id="cart-total" class="text-xl font-bold text-blue-600">R$ 0,00</span>
            </div>
            <div class="flex flex-col space-y-3 mt-6">
                <button class="w-full px-4 py-3 bg-gray-200 text-gray-800 rounded-md text-lg font-semibold hover:bg-gray-300 transition-colors duration-200" onclick="toggleCartModal(false)">
                    Continuar Comprando
                </button>
                <button id="checkout-button" class="w-full px-4 py-3 bg-green-600 text-white rounded-md text-lg font-semibold hover:bg-green-700 transition-colors duration-200" onclick="toggleCheckoutModal(true)">
                    Finalizar Pedido
                </button>
            </div>
        </div>
    </div>

    {{-- MODAL DE CHECKOUT --}}
    <div id="checkout-modal" class="checkout-modal hidden">
        <div class="checkout-modal-content">
            <button class="absolute top-4 right-4 text-gray-500 hover:text-gray-800 text-2xl font-bold" onclick="toggleCheckoutModal(false)">&times;</button>
            <h2 class="text-2xl font-bold text-gray-900 mb-4">Informações de Entrega e Pagamento</h2>
            <div class="border-b border-gray-200 pt-4 pb-2 flex justify-between items-center">
                <span class="text-lg font-bold">Total da Compra:</span>
                <span id="checkout-total-value" class="text-lg font-bold text-blue-600">R$ 0,00</span>
            </div>
            <form id="checkout-form" class="flex flex-col space-y-4">
                <div class="bg-gray-50 p-4 rounded-md">
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Seus Dados</h3>
                    <div>
                        <label for="customer_name_checkout" class="block text-sm font-medium text-gray-700">Seu Nome:</label>
                        <input type="text" id="customer_name_checkout" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required>
                    </div>
                    <div class="mt-4">
                        <label for="customer_phone_checkout" class="block text-sm font-medium text-gray-700">Seu Telefone (WhatsApp):</label>
                        <input type="tel" id="customer_phone_checkout" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" placeholder="Ex: 5511987654321" required>
                    </div>
                    
                    <div class="mt-4">
                        <label for="customer_cep_checkout" class="block text-sm font-medium text-gray-700">CEP:</label>
                        <div class="flex space-x-2">
                            <input type="text" id="customer_cep_checkout" class="mt-1 block w-2/5 border-gray-300 rounded-md shadow-sm" placeholder="Somente números" maxlength="8" disabled>
                            <button type="button" id="search-cep-button" class="mt-1 px-4 py-2 bg-blue-600 text-white rounded-md text-sm font-semibold hover:bg-blue-700 transition-colors duration-200" disabled>
                                Buscar
                            </button>
                             <a href="https://buscacepinter.correios.com.br/app/endereco/index.php" target="_blank" class="mt-2 text-sm text-blue-600 hover:underline">
                                Não sabe seu CEP?
                            </a>
                        </div>
                        
                    </div>
                    <div class="mt-2">
                        <label for="customer_street_checkout" class="block text-sm font-medium text-gray-700">Rua:</label>
                        <input type="text" id="customer_street_checkout" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" placeholder="Ex: R José Barriga" required>
                    </div>
                    <div class="mt-2 flex space-x-2">
                        <div class="w-1/3">
                            <label for="customer_number_checkout" class="block text-sm font-medium text-gray-700">Número:</label>
                            <input type="text" id="customer_number_checkout" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" placeholder="Ex: 2260" required>
                        </div>
                        <div class="w-2/3">
                            <label for="customer_complement_checkout" class="block text-sm font-medium text-gray-700">Complemento:</label>
                            <input type="text" id="customer_complement_checkout" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" placeholder="Ex: Apartamento 101">
                        </div>
                    </div>
                    <div class="mt-2">
                        <label for="customer_neighborhood_checkout" class="block text-sm font-medium text-gray-700">Bairro:</label>
                        <input type="text" id="customer_neighborhood_checkout" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required>
                    </div>
                    <div class="mt-2 flex space-x-2">
                        <div class="w-2/3">
                            <label for="customer_city_checkout" class="block text-sm font-medium text-gray-700">Cidade:</label>
                            <input type="text" id="customer_city_checkout" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required>
                        </div>
                        <div class="w-1/3">
                            <label for="customer_state_checkout" class="block text-sm font-medium text-gray-700">UF:</label>
                            <input type="text" id="customer_state_checkout" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" maxlength="2" required>
                        </div>
                    </div>

                    <div class="mt-4">
                        <label for="order_notes" class="block text-sm font-medium text-gray-700">Observação</label>
                        <textarea id="order_notes" name="order_notes" rows="3" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" placeholder="Ex: Sem cebola, por favor."></textarea>
                    </div>
                </div>

                <div class="bg-gray-50 p-4 rounded-md">
                    <div class="border-b border-gray-200 pt-2 pb-2 flex justify-between items-center">
                        <span class="text-sm font-bold text-gray-600">Distância:</span>
                        <span id="distance-value" class="text-sm font-bold text-gray-600">...</span>
                    </div>
                    <div class="border-b border-gray-200 pt-2 pb-2 flex justify-between items-center">
                        <span class="text-lg font-bold">Valor Entrega:</span>
                        <span id="delivery-fee-value" class="text-lg font-bold text-green-600">Calculando...</span>
                    </div>
                    <div class="pt-2 flex justify-between items-center">
                        <span class="text-xl font-bold">Total a Pagar:</span>
                        <span id="total-with-delivery-value" class="text-xl font-bold text-blue-600">R$ 0,00</span>
                    </div>
                </div>

                <div class="bg-gray-50 p-4 rounded-md">
                        <h3 class="text-lg font-semibold text-gray-900 mb-2">Forma de Pagamento</h3>
                        <div class="flex flex-col space-y-2">
                            <label class="inline-flex items-center">
                                <input type="radio" class="form-radio" name="payment_method" value="Cartão" onchange="handlePaymentChange()" required>
                                <span class="ml-2">Cartão (crédito ou débito)</span>
                            </label>
                            <label class="inline-flex items-center">
                                <input type="radio" class="form-radio" name="payment_method" value="Dinheiro" onchange="handlePaymentChange()" required>
                                <span class="ml-2">Dinheiro</span>
                            </label>
                            <label class="inline-flex items-center">
                                <input type="radio" class="form-radio" name="payment_method" value="Pix" onchange="handlePaymentChange()" required>
                                <span class="ml-2">Pix</span>
                            </label>
                        </div>
                        <div id="troco-field" class="mt-4 hidden">
                            <label for="troco_for" class="block text-sm font-medium text-gray-700">Precisa de troco para quanto?</label>
                            <input type="number" id="troco_for" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" placeholder="Ex: R$ 50,00">
                        </div>
                    </div>
                <div class="flex flex-col space-y-3">
                    <button type="submit" class="w-full px-4 py-3 bg-green-600 text-white rounded-md text-lg font-semibold hover:bg-green-700 transition-colors duration-200">
                        Enviar Pedido por WhatsApp
                    </button>
                    <button type="button" class="w-full px-4 py-3 bg-gray-300 text-gray-700 rounded-md text-lg font-semibold hover:bg-gray-400 transition-colors duration-200" onclick="toggleCheckoutModal(false)">
                        Voltar para o Carrinho
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- Modal de Mensagem --}}
    <div id="message-modal" class="message-modal hidden">
        <div class="message-modal-content text-center">
            <div id="message-icon" class="flex justify-center mb-4"></div>
            <h3 id="message-title" class="text-xl font-bold mb-2"></h3>
            <p id="message-content"></p>
        </div>
    </div>

    <script>
        let cart = JSON.parse(localStorage.getItem('cart')) || {};
        let customerInfo = JSON.parse(localStorage.getItem('customerInfo')) || {};

        const openingTime = document.body.dataset.openingTime;
        const closingTime = document.body.dataset.closingTime;
        const whatsappNumber = document.body.dataset.whatsappNumber;

        let storeLat = null;
        let storeLng = null;
        const storeCep = '78720-130';
        const deliveryFees = [
            { maxDistance: 5, price: 8.00 },
            { maxDistance: 10, price: 12.00 },
            { maxDistance: 15, price: 16.00 }
        ];
        const maxDeliveryDistance = 15;
        let currentDeliveryFee = 0;

        document.addEventListener('DOMContentLoaded', function() {
            updateUI();
            initializeQuantities();
            document.getElementById('cart-modal').classList.add('hidden');
            document.getElementById('checkout-modal').classList.add('hidden');
            document.getElementById('message-modal').classList.add('hidden');
            
            getStoreCoordinates();

            if (customerInfo) {
                document.getElementById('customer_name_checkout').value = customerInfo.name || '';
                document.getElementById('customer_phone_checkout').value = customerInfo.phone || '';
                document.getElementById('customer_cep_checkout').value = customerInfo.cep || '';
                document.getElementById('customer_street_checkout').value = customerInfo.street || '';
                document.getElementById('customer_number_checkout').value = customerInfo.number || '';
                document.getElementById('customer_complement_checkout').value = customerInfo.complement || '';
                document.getElementById('customer_neighborhood_checkout').value = customerInfo.neighborhood || '';
                document.getElementById('customer_city_checkout').value = customerInfo.city || '';
                document.getElementById('customer_state_checkout').value = customerInfo.state || '';
            }

            document.getElementById('checkout-form').addEventListener('submit', function(event) {
                event.preventDefault();
                placeOrder();
            });
            
            const searchCepButton = document.getElementById('search-cep-button');
            const cepInput = document.getElementById('customer_cep_checkout');
            if (searchCepButton) {
                searchCepButton.addEventListener('click', searchCep);
            }
            if (cepInput) {
                cepInput.addEventListener('blur', searchCep);
                cepInput.addEventListener('keydown', function(event) {
                    if (event.key === 'Enter') {
                        event.preventDefault();
                        searchCep();
                    }
                });
            }

            const searchOpenButton = document.getElementById('search-open-button');
            const searchCloseButton = document.getElementById('search-close-button');
            const searchInput = document.getElementById('search-input');
            const searchContainer = document.getElementById('search-container');
            const categoryNav = document.getElementById('category-nav');
            
            searchOpenButton.addEventListener('click', function() {
                searchContainer.classList.remove('scale-x-0');
                searchContainer.classList.add('scale-x-100');
                categoryNav.classList.add('hidden');
                searchInput.focus();
            });

            searchCloseButton.addEventListener('click', function() {
                searchContainer.classList.remove('scale-x-100');
                searchContainer.classList.add('scale-x-0');
                categoryNav.classList.remove('hidden');
                searchInput.value = '';
                filterItems('');
            });

            searchInput.addEventListener('input', function() {
                const query = searchInput.value.toLowerCase();
                filterItems(query);
            });
            
            function filterItems(query) {
                const items = document.querySelectorAll('.menu-item');
                items.forEach(item => {
                    const itemName = item.querySelector('.item-name').textContent.toLowerCase();
                    const itemDescription = item.querySelector('.item-description')?.textContent.toLowerCase() || '';
                    
                    if (itemName.includes(query) || itemDescription.includes(query)) {
                        item.style.display = 'flex';
                    } else {
                        item.style.display = 'none';
                    }
                });
            }
        });

        async function getStoreCoordinates() {
            const cepInput = document.getElementById('customer_cep_checkout');
            const searchCepButton = document.getElementById('search-cep-button');
            try {
                const response = await fetch(`https://cep.awesomeapi.com.br/json/${storeCep}`);
                const data = await response.json();
                
                if (response.status === 200 && data.lat && data.lng) {
                    storeLat = parseFloat(data.lat);
                    storeLng = parseFloat(data.lng);
                    cepInput.disabled = false;
                    searchCepButton.disabled = false;
                    console.log('Coordenadas da Loja obtidas:', storeLat, storeLng);
                } else {
                    console.error('Erro ao obter as coordenadas da loja. Verifique o CEP.');
                    showMessage('Erro na configuração da loja. Não é possível calcular o frete.', 'error');
                }
            } catch (error) {
                console.error('Erro de rede ao buscar o CEP da loja.', error);
                showMessage('Erro de rede. Não é possível calcular o frete.', 'error');
            }
        }

        async function searchCep() {
            const cepInput = document.getElementById('customer_cep_checkout');
            const cep = cepInput.value.replace(/\D/g, '');
            const deliveryFeeSpan = document.getElementById('delivery-fee-value');
            const submitButton = document.getElementById('checkout-form').querySelector('button[type="submit"]');
            const distanceValueSpan = document.getElementById('distance-value');

            if (!storeLat || !storeLng) {
                showMessage('As coordenadas da loja ainda não foram carregadas. Tente novamente em instantes.', 'error');
                return;
            }

            if (cep.length !== 8) {
                showMessage('CEP inválido. Insira um CEP com 8 dígitos.', 'error');
                resetDeliveryFee();
                return;
            }

            deliveryFeeSpan.textContent = 'Calculando...';
            distanceValueSpan.textContent = '...';
            deliveryFeeSpan.classList.remove('text-red-600', 'text-green-600');
            submitButton.disabled = true;

            try {
                const response = await fetch(`https://cep.awesomeapi.com.br/json/${cep}`);
                const data = await response.json();

                if (response.status !== 200) {
                    showMessage(`CEP não encontrado: ${data.message}. Preencha o endereço manualmente.`, 'error');
                    resetDeliveryFee();
                    return;
                }
                
                document.getElementById('customer_street_checkout').value = data.address || '';
                document.getElementById('customer_neighborhood_checkout').value = data.district || '';
                document.getElementById('customer_city_checkout').value = data.city || '';
                document.getElementById('customer_state_checkout').value = data.state || '';
                document.getElementById('customer_number_checkout').focus();

                if (data.lat && data.lng) {
                    const customerLat = parseFloat(data.lat);
                    const customerLng = parseFloat(data.lng);

                    if (isNaN(customerLat) || isNaN(customerLng)) {
                        showMessage('Coordenadas inválidas recebidas da API. Frete indisponível.', 'error');
                        resetDeliveryFee();
                    } else {
                        calculateDistanceAndFee(customerLat, customerLng);
                    }
                } else {
                    showMessage('Não foi possível encontrar as coordenadas para este CEP. Frete indisponível.', 'error');
                    resetDeliveryFee();
                }

            } catch (error) {
                console.error('Erro ao buscar CEP:', error);
                showMessage('Ocorreu um erro na busca do CEP. Tente novamente mais tarde.', 'error');
                resetDeliveryFee();
            }
        }
        
        function calculateDistanceAndFee(customerLat, customerLng) {
            const deliveryFeeSpan = document.getElementById('delivery-fee-value');
            const distanceValueSpan = document.getElementById('distance-value');
            const submitButton = document.getElementById('checkout-form').querySelector('button[type="submit"]');

            const distance = haversineDistance(storeLat, storeLng, customerLat, customerLng);
            distanceValueSpan.textContent = `${distance.toFixed(2)} km`;

            if (distance > maxDeliveryDistance) {
                currentDeliveryFee = -1;
                showMessage(`Desculpe, a distância de ${distance.toFixed(1)} km está fora da nossa área de entrega (até ${maxDeliveryDistance} km).`, 'error');
                deliveryFeeSpan.textContent = 'Não entregamos';
                deliveryFeeSpan.classList.add('text-red-600');
                submitButton.disabled = true;
            } else {
                const fee = deliveryFees.find(f => distance <= f.maxDistance);
                currentDeliveryFee = fee ? fee.price : 0;
                deliveryFeeSpan.textContent = `R$ ${currentDeliveryFee.toFixed(2).replace('.', ',')}`;
                deliveryFeeSpan.classList.remove('text-red-600');
                deliveryFeeSpan.classList.add('text-green-600');
                submitButton.disabled = false;
            }
            
            updateCheckoutTotal();
        }
        
        function resetDeliveryFee() {
            currentDeliveryFee = 0;
            const deliveryFeeSpan = document.getElementById('delivery-fee-value');
            const distanceValueSpan = document.getElementById('distance-value');
            const submitButton = document.getElementById('checkout-form').querySelector('button[type="submit"]');
            
            deliveryFeeSpan.textContent = 'Combinar valor do Frete via whatsapp após  finalizar pedido';
            distanceValueSpan.textContent = '...';
            deliveryFeeSpan.classList.remove('text-green-600');
            deliveryFeeSpan.classList.add('text-red-600');
            submitButton.disabled = true;
            
            updateCheckoutTotal();
        }

        function haversineDistance(lat1, lon1, lat2, lon2) {
            function toRad(value) {
                return value * Math.PI / 180;
            }
            const R = 6371;
            const dLat = toRad(lat2 - lat1);
            const dLon = toRad(lon2 - lon1);
            const a = Math.sin(dLat / 2) * Math.sin(dLat / 2) +
                Math.cos(toRad(lat1)) * Math.cos(toRad(lat2)) *
                Math.sin(dLon / 2) * Math.sin(dLon / 2);
            const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));
            return R * c;
        }
        
        function updateCheckoutTotal() {
            let totalItemsPrice = 0;
            for (const itemId in cart) {
                totalItemsPrice += cart[itemId].quantity * cart[itemId].price;
            }
            
            let finalTotal = totalItemsPrice;
            const deliveryFeeSpan = document.getElementById('delivery-fee-value');
            const submitButton = document.getElementById('checkout-form').querySelector('button[type="submit"]');

            if (currentDeliveryFee === -1) {
                finalTotal = totalItemsPrice;
                deliveryFeeSpan.textContent = 'Não entregamos';
                deliveryFeeSpan.classList.add('text-red-600');
                submitButton.disabled = true;
            } else if (currentDeliveryFee > 0) {
                finalTotal += currentDeliveryFee;
                submitButton.disabled = false;
            } else {
                 if(Object.keys(cart).length > 0) {
                    submitButton.disabled = false;
                } else {
                     submitButton.disabled = true;
                }
            }
            
            document.getElementById('checkout-total-value').textContent = `R$ ${totalItemsPrice.toFixed(2).replace('.', ',')}`;
            document.getElementById('total-with-delivery-value').textContent = `R$ ${finalTotal.toFixed(2).replace('.', ',')}`;
        }

        function initializeQuantities() {
            for (const itemId in cart) {
                const quantitySpans = document.querySelectorAll(`#quantity-${itemId}`);
                if (quantitySpans) {
                    quantitySpans.forEach(span => {
                        span.textContent = cart[itemId].quantity;
                    });
                }
            }
        }

        function isStoreOpen() {
            if (!openingTime || !closingTime) {
                return true;
            }
            const now = new Date();
            const currentHour = now.getHours();
            const currentMinute = now.getMinutes();

            const [openHour, openMinute] = openingTime.split(':').map(Number);
            const [closeHour, closeMinute] = closingTime.split(':').map(Number);

            const openTimeInMinutes = openHour * 60 + openMinute;
            const closeTimeInMinutes = closeHour * 60 + closeMinute;
            const currentTimeInMinutes = currentHour * 60 + currentMinute;
            
            if (closeTimeInMinutes < openTimeInMinutes) {
                return currentTimeInMinutes >= openTimeInMinutes || currentTimeInMinutes < closeTimeInMinutes;
            }
            
            return currentTimeInMinutes >= openTimeInMinutes && currentTimeInMinutes < closeTimeInMinutes;
        }

        function updateUI() {
            updateCartUI();
            
            const storeStatusSpan = document.getElementById('store-status');
            const addToCartButtons = document.querySelectorAll('.btn-add-to-cart');
            
            if (isStoreOpen()) {
                storeStatusSpan.textContent = `Aberto agora, das ${openingTime} às ${closingTime}.`;
                storeStatusSpan.classList.remove('text-red-600');
                storeStatusSpan.classList.add('text-green-600');
                addToCartButtons.forEach(button => button.disabled = false);
            } else {
                storeStatusSpan.textContent = `Fechado agora. Nosso horário de funcionamento é das ${openingTime} às ${closingTime}.`;
                storeStatusSpan.classList.remove('text-green-600');
                storeStatusSpan.classList.add('text-red-600');
                addToCartButtons.forEach(button => button.disabled = true);
            }
        }

        function updateQuantity(itemId, change) {
            const quantitySpans = document.querySelectorAll(`#quantity-${itemId}`);
            
            let currentQuantity = parseInt(quantitySpans[0].textContent);
            let newQuantity = currentQuantity + change;

            if (newQuantity < 0) {
                newQuantity = 0;
            }
            
            quantitySpans.forEach(span => {
                span.textContent = newQuantity;
            });
        }

        function addToCart(itemId, itemName, itemPrice, itemImage) {
            if (!isStoreOpen()) {
                showMessage(`Desculpe, estamos fechados. Nosso horário de funcionamento é das ${openingTime} às ${closingTime}.`, 'error');
                return;
            }

            const quantitySpans = document.querySelectorAll(`#quantity-${itemId}`);
            const quantityToAdd = parseInt(quantitySpans[0].textContent);

            if (quantityToAdd > 0) {
                if (cart[itemId]) {
                    cart[itemId].quantity = quantityToAdd;
                } else {
                    cart[itemId] = {
                        id: itemId,
                        name: itemName,
                        price: itemPrice,
                        quantity: quantityToAdd,
                        image: itemImage
                    };
                }
                saveCart();
                updateUI();
                toggleCartModal(true);
            } else {
                showMessage('A quantidade deve ser maior que zero para adicionar ao carrinho.', 'error');
            }
        }

        function removeFromCart(itemId) {
            if (cart[itemId]) {
                delete cart[itemId];
                saveCart();
                updateUI();
                const quantitySpans = document.querySelectorAll(`#quantity-${itemId}`);
                if (quantitySpans.length > 0) {
                    quantitySpans.forEach(span => {
                        span.textContent = '0';
                    });
                }
            }
        }

        function updateCartItemQuantity(itemId, change) {
            if (cart[itemId]) {
                cart[itemId].quantity += change;
                if (cart[itemId].quantity <= 0) {
                    delete cart[itemId];
                }
                saveCart();
                updateUI();
                const quantitySpans = document.querySelectorAll(`#quantity-${itemId}`);
                if (quantitySpans.length > 0) {
                    quantitySpans.forEach(span => {
                        span.textContent = cart[itemId] ? cart[itemId].quantity : '0';
                    });
                }
            }
        }

        function saveCart() {
            localStorage.setItem('cart', JSON.stringify(cart));
        }

        function updateCartUI() {
            const cartCount = document.getElementById('cart-count');
            const cartItemsContainer = document.getElementById('cart-items');
            const cartTotalSpan = document.getElementById('cart-total');
            
            let totalItems = 0;
            let totalPrice = 0;
            cartItemsContainer.innerHTML = '';

            for (const itemId in cart) {
                const item = cart[itemId];
                totalItems += item.quantity;
                totalPrice += item.quantity * item.price;

                const itemElement = document.createElement('div');
                itemElement.className = 'flex items-center py-2 border-b border-gray-100 last:border-b-0';
                itemElement.innerHTML = `
                    <div class="flex items-start">
                        ${item.image ? `<img src="${item.image}" alt="${item.name}" class="w-12 h-12 object-cover rounded-md mr-3">` : ''}
                        <div>
                            <span class="font-semibold">${item.name}</span>
                            <div class="text-gray-700 text-sm">Quantidade: ${item.quantity}</div>
                            <span class="text-blue-600 font-bold text-sm">R$ ${item.price.toFixed(2).replace('.', ',')}</span>
                        </div>
                    </div>
                    <div class="flex-shrink-0 flex items-center space-x-2">
                        <div class="flex items-center border border-gray-300 rounded-md">
                            <button onclick="updateCartItemQuantity(${item.id}, -1)" class="px-2 py-1 text-gray-800 transition-colors duration-200">-</button>
                            <span class="px-2 font-semibold text-sm">${item.quantity}</span>
                            <button onclick="updateCartItemQuantity(${item.id}, 1)" class="px-2 py-1 text-gray-800 transition-colors duration-200">+</button>
                        </div>
                        <button onclick="removeFromCart(${item.id})" class="text-red-500 hover:text-red-700 text-sm ml-2">Remover</button>
                    </div>
                `;
                cartItemsContainer.appendChild(itemElement);
            }
            
            if (totalItems === 0) {
                cartItemsContainer.innerHTML = '<p class="text-center text-gray-600">Seu carrinho está vazio.</p>';
            }

            cartCount.textContent = totalItems;
            cartTotalSpan.textContent = `R$ ${totalPrice.toFixed(2).replace('.', ',')}`;
        }

        function toggleCartModal(open = null) {
            const cartModal = document.getElementById('cart-modal');
            if (open === true) {
                cartModal.classList.remove('hidden');
            } else if (open === false) {
                cartModal.classList.add('hidden');
            } else {
                cartModal.classList.toggle('hidden');
            }
            updateUI();
        }

        function toggleCheckoutModal(open = null) {
            const checkoutModal = document.getElementById('checkout-modal');
            
            if (Object.keys(cart).length === 0) {
                showMessage('Seu carrinho está vazio. Adicione itens antes de finalizar o pedido.', 'error');
                return;
            }

            if (!isStoreOpen()) {
                showMessage(`Desculpe, estamos fechados. Nosso horário de funcionamento é das ${openingTime} às ${closingTime}.`, 'error');
                return;
            }

            if (open === true) {
                if (customerInfo) {
                    document.getElementById('customer_name_checkout').value = customerInfo.name || '';
                    document.getElementById('customer_phone_checkout').value = customerInfo.phone || '';
                    document.getElementById('customer_cep_checkout').value = customerInfo.cep || '';
                    document.getElementById('customer_street_checkout').value = customerInfo.street || '';
                    document.getElementById('customer_number_checkout').value = customerInfo.number || '';
                    document.getElementById('customer_complement_checkout').value = customerInfo.complement || '';
                    document.getElementById('customer_neighborhood_checkout').value = customerInfo.neighborhood || '';
                    document.getElementById('customer_city_checkout').value = customerInfo.city || '';
                    document.getElementById('customer_state_checkout').value = customerInfo.state || '';
                }
                checkoutModal.classList.remove('hidden');
                toggleCartModal(false);
                updateCheckoutTotal();
                const cepInput = document.getElementById('customer_cep_checkout');
                if (cepInput.value.length === 8) {
                    searchCep();
                }
            } else if (open === false) {
                checkoutModal.classList.add('hidden');
                toggleCartModal(true);
            } else {
                checkoutModal.classList.toggle('hidden');
                if (!checkoutModal.classList.contains('hidden')) {
                    toggleCartModal(false);
                }
            }
        }

        function handlePaymentChange() {
            const paymentMethod = document.querySelector('input[name="payment_method"]:checked').value;
            const trocoField = document.getElementById('troco-field');
            if (paymentMethod === 'Dinheiro') {
                trocoField.classList.remove('hidden');
            } else {
                trocoField.classList.add('hidden');
                document.getElementById('troco_for').value = '';
            }
        }

        function placeOrder() {
            const customerName = document.getElementById('customer_name_checkout').value;
            const customerPhone = document.getElementById('customer_phone_checkout').value;
            const customerCep = document.getElementById('customer_cep_checkout').value.replace(/\D/g, '');
            const customerStreet = document.getElementById('customer_street_checkout').value;
            const customerNumber = document.getElementById('customer_number_checkout').value;
            const customerComplement = document.getElementById('customer_complement_checkout').value;
            const customerNeighborhood = document.getElementById('customer_neighborhood_checkout').value;
            const customerCity = document.getElementById('customer_city_checkout').value;
            const customerState = document.getElementById('customer_state_checkout').value;
            const paymentMethod = document.querySelector('input[name="payment_method"]:checked').value;
            const trocoFor = document.getElementById('troco_for').value;
            const orderNotes = document.getElementById('order_notes').value;

            if (Object.keys(cart).length === 0) {
                showMessage('Seu carrinho está vazio. Adicione itens antes de fazer o pedido.', 'error');
                return;
            }

            if (!customerName || !customerPhone || !customerCep || !customerStreet || !customerNumber || !customerNeighborhood || !customerCity || !customerState) {
                showMessage('Por favor, preencha todos os dados de entrega.', 'error');
                return;
            }

            if (currentDeliveryFee === -1) {
                showMessage('Não podemos finalizar o pedido. O endereço está fora de nossa área de entrega.', 'error');
                return;
            }

            const customerAddress = `${customerStreet}, ${customerNumber}${customerComplement ? ' - ' + customerComplement : ''}, ${customerNeighborhood}, ${customerCity}/${customerState}, CEP ${customerCep}`;

            customerInfo = { 
                name: customerName, 
                phone: customerPhone, 
                cep: customerCep,
                street: customerStreet,
                number: customerNumber,
                complement: customerComplement,
                neighborhood: customerNeighborhood,
                city: customerCity,
                state: customerState
            };
            localStorage.setItem('customerInfo', JSON.stringify(customerInfo));

            let orderSummary = `*Pedido para {{ $user->name }}*\n\n`;
            orderSummary += `*Cliente:* ${customerName}\n`;
            orderSummary += `*Telefone:* ${customerPhone}\n`;
            orderSummary += `*Endereço:* ${customerAddress}\n`;
            if (orderNotes) {
                orderSummary += `*Observação:* ${orderNotes}\n`;
            }
            orderSummary += `*Pagamento:* ${paymentMethod}`;
            if (paymentMethod === 'Dinheiro' && trocoFor) {
                orderSummary += ` (Troco para: R$ ${trocoFor})`;
            }
            orderSummary += `\n\n*Itens do Pedido:*\n`;
            let total = 0;

            for (const itemId in cart) {
                const item = cart[itemId];
                orderSummary += `- ${item.name} (x${item.quantity}) - R$ ${(item.price * item.quantity).toFixed(2).replace('.', ',')}\n`;
                total += item.price * item.quantity;
            }
            
            if (currentDeliveryFee > 0) {
                orderSummary += `\n*Frete:* R$ ${currentDeliveryFee.toFixed(2).replace('.', ',')}\n`;
                total += currentDeliveryFee;
            }

            orderSummary += `\n*Total a Pagar: R$ ${total.toFixed(2).replace('.', ',')}*\n\n`;
            orderSummary += `_Este é um pedido de teste. Por favor, confirme os detalhes._`;

            const encodedMessage = encodeURIComponent(orderSummary);
            const whatsappUrl = `https://api.whatsapp.com/send?phone=${whatsappNumber}&text=${encodedMessage}`;

            window.open(whatsappUrl, '_blank');
            
            cart = {};
            saveCart();
            updateUI();
            
            toggleCheckoutModal(false);
            showMessage('Seu pedido foi enviado para o WhatsApp! Aguarde a confirmação.', 'success');
        }

        function showMessage(message, type = 'info') {
            const messageModal = document.getElementById('message-modal');
            const messageContent = document.getElementById('message-content');
            const messageIcon = document.getElementById('message-icon');
            const messageTitle = document.getElementById('message-title');

            if (type === 'success') {
                messageIcon.innerHTML = `<svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>`;
                messageTitle.textContent = 'Sucesso!';
            } else if (type === 'error') {
                messageIcon.innerHTML = `<svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>`;
                messageTitle.textContent = 'Erro!';
            } else {
                messageIcon.innerHTML = `<svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>`;
                messageTitle.textContent = 'Informação';
            }

            messageContent.textContent = message;
            messageModal.classList.remove('hidden');

            setTimeout(() => {
                messageModal.classList.add('hidden');
            }, 3000);
        }
    </script>
</body>
</html>