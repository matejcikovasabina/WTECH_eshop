<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Category extends Model
{
    // Definujeme, ktoré polia môžeme hromadne napĺňať (napr. v Seederi)
    protected $fillable = ['name', 'category_id'];

    /**
     * Vzťah k produktom: Kategória má mnoho produktov.
     */
    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }

    /**
     * Vzťah k nadradenej kategórii (Parent): 
     * Napr. "Beletria" patrí pod "Knihy".
     */
    public function parent(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    /**
     * Vzťah k podkategóriám (Children): 
     * Napr. "Knihy" majú pod sebou "Beletriu" a "Detektívky".
     */
    public function children(): HasMany
    {
        return $this->hasMany(Category::class, 'category_id');
    }
}
