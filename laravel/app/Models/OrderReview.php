<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderReview extends Model
{
    protected $table = 'order_reviews';

    public $timestamps = false;

    protected $fillable = [
        'order_id',
        'rating',
        'comment',
    ];

    protected $casts = [
        'rating' => 'integer',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id');
    }
}