<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Feast extends Model
{
    protected $fillable = [
        'name',
        'address',
        'maps_url',
        'date',
        'start_time',
    ];
}
