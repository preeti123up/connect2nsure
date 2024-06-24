<?php
namespace App\Models;

use App\Scopes\ActiveScope;
use App\Traits\HasCompany;
use Carbon\Carbon;
use Carbon\CarbonInterval;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Relations\HasMany;


class PasswordReset extends BaseModel
{

    use HasCompany;
}