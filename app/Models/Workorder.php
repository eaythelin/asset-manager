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
      'workorder_type' => WorkorderType::class,
      'status' => WorkorderStatus::class,
      'start_date'=> 'date',
      'end_date' => 'date'
    ];

    protected $fillable = [
      'request_id',
      'completed_by',
      'start_date',
      'end_date',
      'priority_level',
      'workorder_type',
      'status',
      'workorder_code',
      'is_direct'
    ];

    public function disposalWorkOrder(){
      return $this->hasOne(DisposalWorkorder::class);
    }

    public function serviceWorkorder(){
      return $this->hasOne(ServiceWorkorder::class);
    }

    public function requisitionWorkorder(){
      return $this->hasOne(RequisitionWorkorder::class);
    }

    public function request(){
      return $this->belongsTo(Request::class);
    }

    public function completedBy(){
      return $this->belongsTo(User::class, 'completed_by');
    }

    public function getCheckStatusAttribute(){
      if($this->end_date && now()->greaterThan($this->end_date)){
        $protected = ['pending', 'completed', 'cancelled'];

        if(!in_array($this->status->value, $protected) && $this->status->value !== WorkorderStatus::OVERDUE->value){
          $this->update(['status'=> WorkorderStatus::OVERDUE->value]);
          $this->refresh();
        }
      }
    }

    public function scopeSearch($query, $search){
        if (!$search) return $query;

        return $query->where(function($q) use ($search) {
            $q->where('workorder_code', 'LIKE', "%{$search}%")
            ->orWhere('workorder_type', 'LIKE', "%{$search}%")
            ->orWhere('priority_level', 'LIKE', "{$search}")
            ->orWhere('status', 'LIKE', "{$search}")
            ->orWhereRaw("DATE_FORMAT(start_date, '%M %d, %Y') LIKE ?", ["%{$search}%"])
            ->orWhereRaw("DATE_FORMAT(end_date, '%M %d, %Y') LIKE ?", ["%{$search}%"])
            ->orWhereHas('request', function($q2) use ($search) {
                $q2->where('request_code', 'LIKE', "%{$search}%");
            });
        });
    }
}
