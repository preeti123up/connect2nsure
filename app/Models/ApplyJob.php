<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;


class ApplyJob extends BaseModel
{
    use HasFactory;
    protected $table="apply_job";
    
    public function familyDetails()
    {
        return $this->hasMany(FamilyDetail::class, 'candidate_id', 'id');
    }
    public function qualification()
    {
        return $this->hasMany(AcademicQualification::class,'candidate_id','id');
        
    }
    public function work()
    {
        return $this->hasMany(WorkExperience::class,'candidate_id','id');

    }
    public function reference()
    {
        return $this->hasMany(Reference::class,'candidate_id','id');

    }


}