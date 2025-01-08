<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ceremony extends Model
{
   protected $fillable = [
       'name',
       'address',
       'maps_url',
       'date',
       'time'
   ];

   protected $casts = [
       'date' => 'date',
       'time' => 'datetime'
   ];
}
