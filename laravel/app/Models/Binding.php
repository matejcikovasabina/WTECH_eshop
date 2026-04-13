<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Binding extends Model
{
    protected $table = 'bindings';

    public $timestamps = false;

    protected $fillable = [
        'name',
    ];

    public function books()
    {
        return $this->hasMany(Book::class, 'binding_id', 'id');
    }
}
