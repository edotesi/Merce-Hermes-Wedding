<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Appetizer extends Model
{
    protected $fillable = [
        'name',
        'address',
        'maps_url',
        'date',
        'start_time',
    ];
}
