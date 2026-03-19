<?php

namespace App\Models;

use App\Enums\ServiceTypes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
