<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Reception extends Model
{
   protected $fillable = [
       'name',
       'address',
       'maps_url',
       'time'
   ];

   protected $casts = [
       'time' => 'datetime'
   ];
}
