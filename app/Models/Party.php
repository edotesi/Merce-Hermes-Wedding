<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Party extends Model
{
    protected $fillable = [
        'name',
        'address',
        'maps_url',
        'date',
        'start_time',
    ];
}
