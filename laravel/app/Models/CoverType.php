<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CoverType extends Model
{
    protected $fillable = ['name'];

    public function books(): HasMany
    {
        return $this->hasMany(Book::class);
    }
}
