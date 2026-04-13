<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    protected $table = 'invoices';

    protected $primaryKey = 'order_id';

    public $incrementing = false;

    public $timestamps = false;

    protected $keyType = 'int';

    protected $fillable = [
        'order_id',
        'paid',
        'invoice_num',
        'due_date',
    ];

    protected $casts = [
        'paid' => 'boolean',
        'due_date' => 'date',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id');
    }
}