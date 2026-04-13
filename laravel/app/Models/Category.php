<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Category extends Model
{
    // ktore polia mozme hromadne vyplnat v seedeeri
    protected $fillable = ['name', 'category_id'];

    // 1:N ku produktom
    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }

    // stromova struktura na parenta - kategorii (sama na seba)
    public function parent(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    // stromova struktura na deti
    public function children(): HasMany
    {
        return $this->hasMany(Category::class, 'category_id');
    }
}
