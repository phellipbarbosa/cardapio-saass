@extends('layouts.dashboard')

@section('page_title', 'Configurações do Cardápio')

@section('content')
    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6 bg-white border-b border-gray-200">
            <h3 class="text-2xl font-bold mb-4">Gerenciar Informações da Empresa</h3>
            
            @if(session('success'))
                <div class="mb-4 font-medium text-sm text-green-600 bg-green-100 p-3 rounded-md">
                    {{ session('success') }}
                </div>
            @endif

            <form id="settings-form" action="{{ route('admin.settings.update') }}" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-4">
                    <label for="whatsapp_number" class="block font-medium text-sm text-gray-700">Número do WhatsApp</label>
                    <input id="whatsapp_number" class="block mt-1 w-full rounded-md shadow-sm border-gray-300" type="text" name="whatsapp_number" value="{{ old('whatsapp_number', $settings->whatsapp_number) }}" placeholder="Ex: 5511999998888" />
                    @error('whatsapp_number')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="opening_time" class="block font-medium text-sm text-gray-700">Horário de Abertura</label>
                    <input id="opening_time" class="block mt-1 w-full rounded-md shadow-sm border-gray-300" type="time" name="opening_time" value="{{ old('opening_time', $settings->opening_time) }}" />
                    @error('opening_time')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="closing_time" class="block font-medium text-sm text-gray-700">Horário de Fechamento</label>
                    <input id="closing_time" class="block mt-1 w-full rounded-md shadow-sm border-gray-300" type="time" name="closing_time" value="{{ old('closing_time', $settings->closing_time) }}" />
                    @error('closing_time')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <h3 class="text-2xl font-bold mt-8 mb-4">Configurações de Entrega</h3>

                <div class="mb-4">
                    <label for="store_cep" class="block font-medium text-sm text-gray-700">CEP do Estabelecimento</label>
                    <input id="store_cep" class="block mt-1 w-full rounded-md shadow-sm border-gray-300" type="text" name="store_cep" value="{{ old('store_cep', $settings->store_cep) }}" placeholder="Ex: 78720-130" />
                    @error('store_cep')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                
                <div class="mb-4">
                    <label for="max_delivery_distance" class="block font-medium text-sm text-gray-700">Distância Máxima de Entrega (em km)</label>
                    <input id="max_delivery_distance" class="block mt-1 w-full rounded-md shadow-sm border-gray-300" type="number" step="0.01" name="max_delivery_distance" value="{{ old('max_delivery_distance', $settings->max_delivery_distance) }}" />
                    @error('max_delivery_distance')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                
                <div class="mb-4">
                    <h4 class="font-medium text-sm text-gray-700 mb-2">Preços de Entrega por Distância</h4>
                    <div id="delivery-fees-container" class="space-y-2">
                        </div>
                    <button type="button" id="add-fee-button" class="mt-2 px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600">
                        Adicionar Faixa de Preço Entrega
                    </button>
                    <input type="hidden" name="delivery_fees" id="delivery_fees_input">
                </div>
                
                <div class="flex items-center justify-end mt-4">
                    <button type="submit" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:ring ring-gray-300 disabled:opacity-25 transition ml-4">
                        {{ __('Salvar Configurações') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const settings = @json($settings);
        const form = document.getElementById('settings-form');
        const feesContainer = document.getElementById('delivery-fees-container');
        const addButton = document.getElementById('add-fee-button');
        const deliveryFeesInput = document.getElementById('delivery_fees_input');

        let deliveryFees = [];

        if (settings.delivery_fees) {
            try {
                deliveryFees = JSON.parse(settings.delivery_fees);
            } catch (e) {
                console.error('Erro ao fazer o parse das taxas de entrega:', e);
                deliveryFees = [];
            }
        }

        function renderFees() {
            feesContainer.innerHTML = '';
            deliveryFees.sort((a, b) => a.maxDistance - b.maxDistance);
            deliveryFees.forEach((fee, index) => {
                const div = document.createElement('div');
                div.className = 'flex items-center space-x-2';
                div.innerHTML = `
                    <div class="flex-grow">
                        <label class="block text-xs text-gray-700">Até (km):</label>
                        <input type="number" step="0.01" value="${fee.maxDistance}" class="mt-1 block w-full rounded-md shadow-sm border-gray-300 max-distance-input">
                    </div>
                    <div class="flex-grow">
                        <label class="block text-xs text-gray-700">Preço (R$):</label>
                        <input type="number" step="0.01" value="${fee.price}" class="mt-1 block w-full rounded-md shadow-sm border-gray-300 price-input">
                    </div>
                    <button type="button" class="px-3 py-1 text-red-500 hover:text-red-700 remove-fee-button">
                        Remover
                    </button>
                `;
                feesContainer.appendChild(div);
                div.querySelector('.remove-fee-button').addEventListener('click', () => removeFee(index));
            });
        }

        function addFee() {
            deliveryFees.push({ maxDistance: '', price: '' });
            renderFees();
        }

        function removeFee(index) {
            deliveryFees.splice(index, 1);
            renderFees();
        }

        form.addEventListener('submit', function(event) {
            const currentFees = [];
            feesContainer.querySelectorAll('.flex').forEach(feeRow => {
                const maxDistance = feeRow.querySelector('.max-distance-input').value;
                const price = feeRow.querySelector('.price-input').value;
                if (maxDistance && price) {
                    currentFees.push({
                        maxDistance: parseFloat(maxDistance),
                        price: parseFloat(price)
                    });
                }
            });
            deliveryFeesInput.value = JSON.stringify(currentFees);
        });

        // Esta é a linha que conecta o botão ao JavaScript
        addButton.addEventListener('click', addFee);
        renderFees(); // Renderiza a tabela inicial
    });
</script>
@endsection