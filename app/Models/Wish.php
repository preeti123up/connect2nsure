<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Wish extends BaseModel
{
    use HasFactory;
   public function reply()
{
    return $this->hasMany(Reply::class);
}

// Inside the Wish model
public function wishedBy()
{
    return $this->belongsTo(User::class, 'wished_by');
}

   
   
}
