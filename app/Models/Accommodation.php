<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Accommodation extends Model
{
    protected $fillable = [
        'name',
        'zone',
        'stars',
        'distance',
        'website',
        'discount_info',
        'is_farinera_group',
        'order'
    ];
}
