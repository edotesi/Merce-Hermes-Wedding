<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Gift extends Model
{
   protected $fillable = [
       'name',
       'price',
       'image_url',
       'is_purchased',
       'purchased_at',
       'purchaser_name',
       'purchaser_email',
       'unique_code'
   ];

   protected $casts = [
       'is_purchased' => 'boolean',
       'purchased_at' => 'datetime',
       'price' => 'decimal:2'
   ];
}
