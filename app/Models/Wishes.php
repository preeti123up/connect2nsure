<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Wishes extends BaseModel
{
    use HasFactory;
    protected $table='wishes_setting';
    protected $fillable = ['type','company_id','background_image','app_image','font_color','rtl','message','added_by'];

    public function celebration_type(){
        return $this->belongsTo(CelebrationType::class,'type');
    }
}
