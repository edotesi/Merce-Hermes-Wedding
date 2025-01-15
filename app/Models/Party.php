<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Party extends Model
{
    protected $fillable = [
        'name',
        'description',
        'address',
        'maps_url',
        'start_time',
        'end_time'
    ];
}
