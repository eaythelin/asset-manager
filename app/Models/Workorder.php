<?php

namespace App\Models;

use App\Enums\PriorityLevel;
use App\Enums\WorkorderStatus;
use App\Enums\WorkorderType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Workorder extends Model
{
    use HasFactory, SoftDeletes;

    protected $casts = [
      'priority_level' => PriorityLevel::class,
      'type' => WorkorderType::class,
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
      'type',
      'status',
      'workorder_code'
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
}
