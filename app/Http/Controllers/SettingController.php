<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SettingController extends Controller
{
    /**
     * Exibe o formulário de edição das configurações.
     */
    public function edit()
    {
        $settings = Auth::user()->setting()->firstOrCreate([]);
        // Certifique-se de que a view esteja esperando $settings
        return view('admin.settings.edit', compact('settings'));
    }

    /**
     * Atualiza as configurações.
     */
    public function update(Request $request)
    {
        $request->validate([
            'whatsapp_number' => ['nullable', 'string', 'max:20'],
            'opening_time' => ['nullable', 'string'],
            'closing_time' => ['nullable', 'string'],
            'store_cep' => ['nullable', 'string', 'regex:/^\d{5}-?\d{3}$/'], // Novo campo
            'max_delivery_distance' => ['nullable', 'numeric'], // Novo campo
            'delivery_fees' => ['nullable', 'string'], // Recebe a string JSON do frontend
        ]);

        $data = $request->only([
            'whatsapp_number',
            'opening_time',
            'closing_time',
            'store_cep',
            'max_delivery_distance',
            'delivery_fees'
        ]);

        $data['opening_time'] = $data['opening_time'] === '' ? null : $data['opening_time'];
        $data['closing_time'] = $data['closing_time'] === '' ? null : $data['closing_time'];
        
        Auth::user()->setting()->updateOrCreate(
            ['user_id' => Auth::id()],
            $data
        );

        return redirect()->route('admin.settings.edit')->with('success', 'Configurações salvas com sucesso!');
    }

    /**
     * Endpoint da API para buscar as configurações.
     * Este método não precisa ser alterado para a lógica de salvar.
     */
    public function getDeliverySettings()
    {
        // ... a lógica do método getDeliverySettings permanece a mesma
    }
}