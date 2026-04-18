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
        'end_of_life_date',
        'status',
        'quantity',
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

    public function disposalWorkorders(){
        return $this->hasMany(DisposalWorkorder::class);
    }

    public function serviceWorkorders(){
        return $this->hasMany(ServiceWorkorder::class);
    }

    public function requisitionWorkorders(){
        return $this->hasMany(RequisitionWorkorder::class);
    }

    public function getBookValueAttribute(){
        //this is Straight Line Depreciation
        if(!$this->is_depreciable){
            return $this->cost;
        }

        $startDate = $this->acquisition_date;
        $endDate = now();

        if($this->status === AssetStatus::DISPOSED){
            $latestDisposal = $this->disposalWorkorders()->latest('disposal_date')->first();

            if($latestDisposal){
                $endDate = $latestDisposal->disposal_date;
            }
        }
        
        $totalMonths = $totalMonths = $this->useful_life_in_years * 12;
        $monthElapsed = $startDate->diffInMonths($endDate);
        $monthsToDepreciate = min($monthElapsed, $totalMonths);
        $monthlyDepreciation = ($this->cost - $this->salvage_value) / $totalMonths;
        $accumulatedDepreciation = $monthlyDepreciation * $monthElapsed;

        $currentValue = max($this->cost - $accumulatedDepreciation, $this->salvage_value);
        return $currentValue;
    }

    public function getAccumulatedDepreciationAttribute(){
        if(!$this->is_depreciable || !$this->acquisition_date || !$this->useful_life_in_years){
            return 0;
        }

        $startDate = $this->acquisition_date;
        $endDate = now();

        if($this->status === AssetStatus::DISPOSED){
            $latestDisposal = $this->disposalWorkorders()->latest('disposal_date')->first();

            if($latestDisposal){
                $endDate = $latestDisposal->disposal_date;
            }
        }
        
        $totalMonths = $totalMonths = $this->useful_life_in_years * 12;
        $monthElapsed = $startDate->diffInMonths($endDate);
        $monthsToDepreciate = min($monthElapsed, $totalMonths);
        $monthlyDepreciation = ($this->cost - $this->salvage_value) / $totalMonths;

        return round($monthlyDepreciation * $monthsToDepreciate, 2);
    }

    public function getComputedStatusAttribute(){
        if($this->is_depreciable && $this->end_of_life_date){
            if(now()->greaterThan($this->end_of_life_date)){
                $protected = ['disposed', 'under_service'];

                if(!in_array($this->status->value, $protected) && $this->status->value !== AssetStatus::EXPIRED->value){
                    $this->update(['status'=> AssetStatus::EXPIRED->value]);
                    $this->refresh();
                }
                
                return 'expired';
            }
        }

        return $this->status->value;
    }

    protected static function booted(){
        static::deleted(function($asset){
            $asset->serviceWorkorders->each(function($service) {
                if ($service->workorder) {
                    $service->workorder()
                        ->whereNotIn('status', ['completed', 'cancelled'])
                        ->update(['status' => 'cancelled']);
                }
            });

            $asset->disposalWorkorders->each(function($disposal) {
                if ($disposal->workorder) {
                    $disposal->workorder()
                        ->whereNotIn('status', ['completed', 'cancelled'])
                        ->update(['status' => 'cancelled']);
                }
            });

            $asset->requisitionWorkorders->each(function($requisition) {
                if ($requisition->workorder) {
                    $requisition->workorder()
                        ->whereNotIn('status', ['completed', 'cancelled'])
                        ->update(['status' => 'cancelled']);
                }
            });
        });
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
            });
        });
    }
}
