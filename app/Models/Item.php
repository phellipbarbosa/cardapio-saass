<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Item extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'price',
        'image_path',
        'category_id',
        'is_active',
        'is_featured', // <--- ADICIONE ESTA LINHA
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }
}