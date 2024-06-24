<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class AssignBranch extends Model
{
    use HasFactory;

    protected $table = "assign_branch";
    protected $fillable = ['branch_id','user_id'];
    
      public function branch()
    {
        return $this->belongsTo(Branch::class, 'branch_id', 'id');
    }

}
