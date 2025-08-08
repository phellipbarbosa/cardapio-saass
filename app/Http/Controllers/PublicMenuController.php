<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Http\Request;

class PublicMenuController extends Controller
{
    public function show(string $slug)
    {
        $user = User::where('slug', $slug)->firstOrFail();
        $settings = $user->setting()->firstOrCreate([]);
        
        // --- ADICIONE ESTAS VARIÁVEIS PARA O FRETE ---
        $storeCep = $settings->store_cep ?? null;
        $maxDeliveryDistance = $settings->max_delivery_distance ?? 0;
        $deliveryFees = $settings->delivery_fees ?? '[]';
        // --- FIM DA ADIÇÃO ---
        
        $whatsappNumber = $settings->whatsapp_number;
        $openingTime = $settings->opening_time;
        $closingTime = $settings->closing_time;

        $categories = $user->categories()
                           ->where('is_active', true)
                           ->with(['items' => function ($query) {
                               $query->where('is_active', true);
                           }])
                           ->orderBy('name')
                           ->get();
        
        $featuredItems = $user->items()
                              ->where('is_featured', true)
                              ->get();

        return view('public_menu', compact(
            'user', 
            'categories', 
            'whatsappNumber', 
            'openingTime', 
            'closingTime', 
            'featuredItems',
            // --- ADICIONE ESTES NOMES AQUI ---
            'storeCep', 
            'maxDeliveryDistance', 
            'deliveryFees'
            // --- FIM DA ADIÇÃO ---
        ));
    }
}