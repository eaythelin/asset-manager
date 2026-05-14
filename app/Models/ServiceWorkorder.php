<?php

namespace App\Models;

use App\Enums\ServiceTypes;
use App\Enums\MaintenanceType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property int $workorder_id
 * @property int|null $asset_id
 * @property ServiceTypes $service_type
 * @property MaintenanceType|null $maintenance_type
 * @property numeric $cost
 * @property string|null $subcontractor_name
 * @property string|null $subcontractor_details
 * @property int|null $assigned_to
 * @property int|null $estimated_hours
 * @property string|null $instructions
 * @property string|null $accomplishment_report
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Asset|null $asset
 * @property-read \App\Models\Employee|null $assignedTo
 * @property-read \App\Models\Workorder $workorder
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ServiceWorkorder newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ServiceWorkorder newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ServiceWorkorder query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ServiceWorkorder whereAccomplishmentReport($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ServiceWorkorder whereAssetId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ServiceWorkorder whereAssignedTo($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ServiceWorkorder whereCost($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ServiceWorkorder whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ServiceWorkorder whereEstimatedHours($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ServiceWorkorder whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ServiceWorkorder whereInstructions($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ServiceWorkorder whereMaintenanceType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ServiceWorkorder whereServiceType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ServiceWorkorder whereSubcontractorDetails($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ServiceWorkorder whereSubcontractorName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ServiceWorkorder whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ServiceWorkorder whereWorkorderId($value)
 * @mixin \Eloquent
 */
class ServiceWorkorder extends Model
{
    use HasFactory;

    protected $fillable = [
        'workorder_id',
        'asset_id',
        'service_type',
        'maintenance_type',
        'cost',
        
        // Subcontractor fields
        'subcontractor_name',
        'subcontractor_details',
        
        // In House fields
        'assigned_to',
        'estimated_hours',
        
        // Completion fields
        'instructions',
        'accomplishment_report',
    ];

    protected $casts = [
        'service_type' => ServiceTypes::class,
        'maintenance_type'=> MaintenanceType::class,
    ];

    public function workorder(){
        return $this->belongsTo(Workorder::class);
    }

    public function asset(){
        return $this->belongsTo(Asset::class);
    }

    public function assignedTo(){
        return $this->belongsTo(Employee::class, 'assigned_to');
    }
}
