<?php

namespace App\Models;

use App\Enums\DisposalConditions;
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
        'service_type',
        'condition',
        'quantity',
        'is_new_asset',
        'department_id'
    ];

    protected $casts = [
        'type' => RequestTypes::class,
        'service_type' => ServiceTypes::class,
        'status' => RequestStatus::class,
        'condition' => DisposalConditions::class,
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
        return $this->belongsTo(Asset::class)->withTrashed();
    }

    public function workorder(){
        return $this->hasOne(Workorder::class);
    }

    public function files(){
        return $this->hasMany(RequestFile::class);
    }

    public function department(){
        return $this->belongsTo(Department::class);
    }

    public function scopeSearch($query, $search){
        if (!$search) return $query;

        return $query->where(function($q) use ($search) {
            $q->where('request_code', 'LIKE', "%{$search}%")
            ->orWhere('type', 'LIKE', "%{$search}%")
            ->orWhereRaw("DATE_FORMAT(date_requested, '%M %d, %Y') LIKE ?", ["%{$search}%"])
            ->orWhere(function($query) use ($search){
                $query->whereNotNull('asset_name')
                        ->where('asset_name', 'LIKE', "%{$search}%")
                        ->orWhereHas('asset', function($q) use($search){
                            $q->where('name', 'LIKE', "%{$search}%");
                        });
            })
            ->orWhereHas('category', function($q2) use ($search) {
                $q2->where('name', 'LIKE', "%{$search}%");
            })
            ->orWhereHas('requestedBy', function($q2) use ($search) {
                $q2->where('name', 'LIKE', "%{$search}%");
            }); 
        });
    }
}
