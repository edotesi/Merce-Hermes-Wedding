<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WeddingInfo extends Model
{
   protected $fillable = [
       'iban',
       'dress_code'
   ];
}
