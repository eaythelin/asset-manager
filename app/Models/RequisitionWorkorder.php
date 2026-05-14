<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * @property int $id
 * @property int $workorder_id
 * @property int|null $asset_id
 * @property \Illuminate\Support\Carbon|null $acquisition_date
 * @property string|null $asset_name
 * @property numeric|null $estimated_cost
 * @property int|null $supplier_id
 * @property string|null $description
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Asset|null $asset
 * @property-read \App\Models\Supplier|null $supplier
 * @property-read \App\Models\Workorder $workorder
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RequisitionWorkorder newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RequisitionWorkorder newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RequisitionWorkorder query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RequisitionWorkorder whereAcquisitionDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RequisitionWorkorder whereAssetId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RequisitionWorkorder whereAssetName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RequisitionWorkorder whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RequisitionWorkorder whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RequisitionWorkorder whereEstimatedCost($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RequisitionWorkorder whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RequisitionWorkorder whereSupplierId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RequisitionWorkorder whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RequisitionWorkorder whereWorkorderId($value)
 * @mixin \Eloquent
 */
class RequisitionWorkorder extends Model
{
    use HasFactory;
    protected $fillable = [
        'workorder_id',
        'acquisition_date',
        'estimated_cost',
        'supplier_id',
        'description',
        'asset_name',
        'asset_id'
    ];

    protected $casts = [
        'acquisition_date'=> 'date'
    ];

    public function workorder(){
        return $this->belongsTo(Workorder::class);
    }

    public function supplier(){
        return $this->belongsTo(Supplier::class);
    }

    public function asset(){
        return $this->belongsTo(Asset::class);
    }
}
