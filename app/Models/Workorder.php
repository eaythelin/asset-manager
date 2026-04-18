<?php

namespace App\Models;

use App\Enums\PriorityLevel;
use App\Enums\WorkorderStatus;
use App\Enums\WorkorderType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
