<?php

namespace App\Models;

// ... outras importações ...
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasManyThrough; // <--- Adicione esta importação

class User extends Authenticatable
{
    // ... use traits e outras propriedades ...
    
    // Relações do Eloquent
    public function setting(): HasOne
    {
        return $this->hasOne(Setting::class);
    }
    
    public function categories(): HasMany
    {
        return $this->hasMany(Category::class);
    }

    // <--- ADICIONE ESTA NOVA RELAÇÃO AO SEU MODELO USER
    public function items(): HasManyThrough 
    {
        return $this->hasManyThrough(Item::class, Category::class)->where('items.is_active', true);
    }
    // <--- FIM DA NOVA RELAÇÃO
}