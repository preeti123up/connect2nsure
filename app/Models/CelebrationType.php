<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CelebrationType extends Model
{
    use HasFactory;
    
    
    protected $fillable = [
        'celebration_type', 'celebration_value',
    ];
}
