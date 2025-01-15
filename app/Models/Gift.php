<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Gift extends Model
{
    protected $fillable = [
        'name',
        'price',
        'image_url',
        'status',
        'stock',
        'reserved_stock',
        'reserved_at',
        'reservation_expires_at',
        'purchased_at',
        'purchaser_name',
        'purchaser_email',
        'store',
        'order_number',
        'product_url',
        'unique_code'
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'reserved_at' => 'datetime',
        'reservation_expires_at' => 'datetime',
        'purchased_at' => 'datetime',
        'stock' => 'integer',
        'reserved_stock' => 'integer'
    ];

    public function isAvailable()
    {
        return $this->status === 'available' && $this->stock > $this->reserved_stock;
    }

    public function canBeReserved()
    {
        return $this->isAvailable() && ($this->stock - $this->reserved_stock) > 0;
    }
}
