<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class EmployeeBranch extends Model
{
    use HasFactory;

    protected $fillable = [
        'emp_id',
        'branch_id'
    ];
}
