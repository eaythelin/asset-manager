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
        'cost',
        'done_by',
        'is_vehicle',
        'details'
    ];

    protected $casts = [
        'service_type' => ServiceTypes::class
    ];

    public function workorder(){
        return $this->belongsTo(Workorder::class);
    }

    public function asset(){
        return $this->belongsTo(Asset::class);
    }
}
