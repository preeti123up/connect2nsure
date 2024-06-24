<?php
namespace App\Models;

use App\Scopes\ActiveScope;
use App\Traits\HasCompany;
use Carbon\Carbon;
use Carbon\CarbonInterval;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Relations\HasMany;


class Asset extends BaseModel
{

    use HasCompany;
    public function assetDevice()
    {
        return $this->belongsTo(AssetDevice::class, 'asset_type', 'id');
    }

    public function lendedAsset(): HasMany
    {
        return $this->hasMany(LendAsset::class);
    }
}