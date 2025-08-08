<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Setting extends Model
{
    use HasFactory;
    
    protected $fillable = [
         'user_id',
        'whatsapp_number',
        'opening_time',
        'closing_time',
        'store_cep', // Adicione esta linha
        'max_delivery_distance', // Adicione esta linha
        'delivery_fees', // Adicione esta linha
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}