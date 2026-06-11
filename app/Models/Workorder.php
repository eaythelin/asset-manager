<?php

namespace App\Models;

use App\Enums\PriorityLevel;
use App\Enums\WorkorderStatus;
use App\Enums\WorkorderType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property string $workorder_code
 * @property int|null $request_id
 * @property int|null $completed_by
 * @property \Illuminate\Support\Carbon|null $start_date
 * @property \Illuminate\Support\Carbon|null $end_date
 * @property PriorityLevel $priority_level
 * @property WorkorderType $workorder_type
 * @property WorkorderStatus $status
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int $is_direct
 * @property-read \App\Models\User|null $completedBy
 * @property-read \App\Models\DisposalWorkorder|null $disposalWorkOrder
 * @property-read mixed $check_status
 * @property-read \App\Models\Request|null $request
 * @property-read \App\Models\RequisitionWorkorder|null $requisitionWorkorder
 * @property-read \App\Models\ServiceWorkorder|null $serviceWorkorder
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Workorder newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Workorder newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Workorder query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Workorder search($search)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Workorder whereCompletedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Workorder whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Workorder whereEndDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Workorder whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Workorder whereIsDirect($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Workorder wherePriorityLevel($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Workorder whereRequestId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Workorder whereStartDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Workorder whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Workorder whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Workorder whereWorkorderCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Workorder whereWorkorderType($value)
 * @mixin \Eloquent
 */
class Workorder extends Model
{
    use HasFactory;

    protected $casts = [
      'priority_level' => PriorityLevel::class,
      'status' => WorkorderStatus::class,
      'started_at' => 'datetime',
      'finished_at' => 'datetime',
      'sub_date_released' => 'date',
      'sub_date_returned'=> 'date',
      'spare_parts' => 'array',
      'last_change_oil_date' => 'date',
      'insurance_date' => 'date',
      'registration_date' => 'date'
    ];

    protected $fillable = [
        'workorder_code',
        'request_id',
        'completed_by',
        'status',
        // in-house
        'is_inhouse',
        'priority_level',
        'inhouse_cost',
        'estimated_duration',
        'instructions',
        // subcontractor
        'is_subcontractor',
        'sub_name',
        'sub_document',
        'sub_details',
        'sub_cost',
        'sub_date_released',
        'sub_date_returned',
        // accomplishment
        'started_at',
        'finished_at',
        'accomplishment_details',
        // spare parts
        'has_spare_parts',
        'spare_parts',
        // vehicle
        'has_vehicle',
        'has_minor',
        'vehicle_minor_details',
        'has_major',
        'vehicle_major_details',
        'has_change_oil',
        'last_change_oil_date',
        'meter_reading',
        'has_insurance',
        'insurance_date',
        'has_registration',
        'registration_date',
        'has_other',
        'other_details',
    ];

    public function request(){
      return $this->belongsTo(Request::class);
    }

    public function completedBy(){
      return $this->belongsTo(User::class, 'completed_by');
    }

    public function assignedEmployees(){
        return $this->belongsToMany(Employee::class, 'workorder_employees');
    }

    public function scopeSearch($query, $search){
        if (!$search) return $query;

        return $query->where(function($q) use ($search) {

        });
    }
}
