<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AcquisitionWorkorder extends Model
{
    use HasFactory;
    protected $fillable = [
        'workorder_id',
        'acquisition_date',
        'estimated_cost',
        'supplier_id',
        'description'
    ];

    public function workorder(){
        return $this->belongsTo(Workorder::class);
    }

    public function supplier(){
        return $this->belongsTo(Supplier::class);
    }
}
