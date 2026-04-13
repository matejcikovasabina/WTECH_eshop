<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Giftcard extends Model
{
    protected $table = 'giftcards';

    protected $primaryKey = 'product_id';

    public $incrementing = false;

    public $timestamps = false;

    protected $keyType = 'int';

    protected $fillable = [
        'product_id',
        'value',
        'code',
        'expires_at',
    ];

    protected $casts = [
        'expires_at' => 'datetime',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id', 'id');
    }
}