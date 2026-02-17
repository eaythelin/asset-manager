<?php

namespace App\Models;

use App\Enums\RequestStatus;
use App\Enums\RequestTypes;
use App\Enums\ServiceTypes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Request extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = [
        'description',
        'date_requested',
        'date_approved',
        'asset_name',
        'requested_by',
        'category_id',
        'sub_category_id',
        'handled_by',
        'asset_id',
        'request_code',
        'type',
        'status',
        'service_type'
    ];

    protected $casts = [
        'type' => RequestTypes::class,
        'service_type' => ServiceTypes::class,
        'status' => RequestStatus::class,
        'date_requested' => 'date'
    ];

    public function category(){
        return $this->belongsTo(Category::class);
    }

    public function subCategory(){
        return $this->belongsTo(SubCategory::class);
    }

    public function requestedBy(){
        return $this->belongsTo(User::class, 'requested_by');
    }

    public function approvedBy(){
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function asset(){
        return $this->belongsTo(Asset::class);
    }

    public function workorder(){
        return $this->hasOne(Workorder::class);
    }

    public function files(){
        return $this->hasMany(RequestFile::class);
    }
}
