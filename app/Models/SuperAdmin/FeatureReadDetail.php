<?php

namespace App\Models\SuperAdmin;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FeatureReadDetail extends BaseModel
{
    use HasFactory;

   
        public function featureDetail()
        {
            return $this->belongsTo(Feature::class,'feature_id','id');
        }
  
}
