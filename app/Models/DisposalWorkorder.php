<?php

namespace App\Models;

use App\Enums\DisposalMethods;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property int $workorder_id
 * @property int|null $asset_id
 * @property DisposalMethods|null $disposal_method
 * @property \Illuminate\Support\Carbon|null $disposal_date
 * @property int|null $quantity
 * @property string|null $reason
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Asset|null $asset
 * @property-read \App\Models\Workorder $workorder
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DisposalWorkorder newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DisposalWorkorder newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DisposalWorkorder query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DisposalWorkorder whereAssetId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DisposalWorkorder whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DisposalWorkorder whereDisposalDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DisposalWorkorder whereDisposalMethod($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DisposalWorkorder whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DisposalWorkorder whereQuantity($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DisposalWorkorder whereReason($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DisposalWorkorder whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DisposalWorkorder whereWorkorderId($value)
 * @mixin \Eloquent
 */
class DisposalWorkorder extends Model
{
    use HasFactory;

    protected $fillable =  [
        "workorder_id",
        "asset_id",
        "disposal_method",
        "disposal_date",
        "reason",
        'quantity'
    ];

    protected $casts = [
        "disposal_method" => DisposalMethods::class,
        "disposal_date" => "date"
    ];

    public function workorder(){
        return $this->belongsTo(Workorder::class);
    }

    public function asset(){
        return $this->belongsTo(Asset::class)->withTrashed();
    }
}
