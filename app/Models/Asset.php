<?php

namespace App\Models;

use App\Enums\AssetStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;

class Asset extends Model
{
    use HasFactory, SoftDeletes;

    protected $casts = [
      'status' => AssetStatus::class,
      'acquisition_date' => 'date',
      'end_of_life_date' => 'date'
    ];

    protected $fillable = [
        'asset_code',
        'name',
        'serial_name',
        'description',
        'is_depreciable',
        'acquisition_date',
        'cost',
        'image_path',
        'category_id',
        'sub_category_id',
        'supplier_id',
        'custodian_id',
        'department_id',
        'useful_life_in_years',
        'salvage_value',
        'end_of_life_date'
    ];

    public function custodian(){
        return $this->belongsTo(Employee::class, 'custodian_id');
    }

    public function department(){
        return $this->belongsTo(Department::class);
    }

    public function category(){
        return $this->belongsTo(Category::class);
    }

    public function subCategory(){
        return $this->belongsTo(SubCategory::class);
    }

    public function supplier(){
        return $this->belongsTo(Supplier::class);
    }

    public function requests(){
        return $this->hasMany(Request::class);
    }

    public function disposalWorkorder(){
        return $this->hasOne(DisposalWorkorder::class);
    }

    public function serviceWorkorders(){
        return $this->hasMany(ServiceWorkorder::class);
    }

    public function getBookValueAttribute(){
        //this is Straight Line Depreciation
        if(!$this->is_depreciable){
            return null;
        }

        if($this->status === AssetStatus::DISPOSED){
            return $this->salvage_value;
        }

        $monthElapsed = floor(Carbon::parse($this->acquisition_date)->diffInMonths(now())); //this keeps returning a decimal i dont know why >:(
        $totalMonths = $this->useful_life_in_years * 12;
        //so asset monthlapsed wont go over useful life months
        $monthElapsed = min($monthElapsed, $totalMonths);

        $monthlyDepreciation = ($this->cost - $this->salvage_value) / $totalMonths;
        $accumulatedDepreciation = $monthlyDepreciation * $monthElapsed;

        $bookValue = max($this->cost - $accumulatedDepreciation, $this->salvage_value);
        return round($bookValue, 2);
    }

    public function getComputedStatusAttribute(){
        if($this->deleted_at){
            return 'disposed';
        }

        if($this->is_depreciable && $this->end_of_life_date){
            if(now()->greaterThan($this->end_of_life_date)){
                return 'expired';
            }
        }

        return $this->status->value;
    }

    public function scopeSearch($query, $search){
        if (!$search) return $query;

        return $query->where(function($q) use ($search) {
            $q->where('asset_code', 'LIKE', "%{$search}%")
            ->orWhere('name', 'LIKE', "%{$search}%")
            ->orWhere('serial_name', 'LIKE', "%{$search}%")
            ->orWhereHas('department', function($q2) use ($search) {
                $q2->where('name', 'LIKE', "%{$search}%");
            })
            ->orWhereHas('custodian', function($q2) use ($search) {
                $q2->where('first_name', 'LIKE', "%{$search}%")
                ->orWhere('last_name', 'LIKE', "%{$search}%")
                ->orWhereRaw("CONCAT(first_name,' ',last_name) LIKE ?", ["%{$search}%"]);
            })
            ->orWhereHas('category', function($q2) use ($search) {
                $q2->where('name', 'LIKE', "%{$search}%");
            });;
        });
    }
}
