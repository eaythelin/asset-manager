<?php

namespace App\Models;

use App\Enums\AssetStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;

/**
 * @property int $id
 * @property string $asset_code
 * @property string $name
 * @property string|null $serial_name
 * @property AssetStatus $status
 * @property string|null $description
 * @property int $is_depreciable
 * @property string|null $image_path
 * @property int $quantity
 * @property int|null $category_id
 * @property int|null $department_id
 * @property int|null $sub_category_id
 * @property int|null $supplier_id
 * @property int|null $custodian_id
 * @property \Illuminate\Support\Carbon|null $acquisition_date
 * @property numeric $cost
 * @property numeric $salvage_value
 * @property int|null $useful_life_in_years
 * @property \Illuminate\Support\Carbon|null $end_of_life_date
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Category|null $category
 * @property-read \App\Models\Employee|null $custodian
 * @property-read \App\Models\Department|null $department
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\DisposalWorkorder> $disposalWorkorders
 * @property-read int|null $disposal_workorders_count
 * @property-read mixed $accumulated_depreciation
 * @property-read mixed $book_value
 * @property-read mixed $computed_status
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Request> $requests
 * @property-read int|null $requests_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\RequisitionWorkorder> $requisitionWorkorders
 * @property-read int|null $requisition_workorders_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\ServiceWorkorder> $serviceWorkorders
 * @property-read int|null $service_workorders_count
 * @property-read \App\Models\SubCategory|null $subCategory
 * @property-read \App\Models\Supplier|null $supplier
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Asset newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Asset newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Asset onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Asset query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Asset search($search)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Asset whereAcquisitionDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Asset whereAssetCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Asset whereCategoryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Asset whereCost($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Asset whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Asset whereCustodianId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Asset whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Asset whereDepartmentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Asset whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Asset whereEndOfLifeDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Asset whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Asset whereImagePath($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Asset whereIsDepreciable($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Asset whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Asset whereQuantity($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Asset whereSalvageValue($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Asset whereSerialName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Asset whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Asset whereSubCategoryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Asset whereSupplierId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Asset whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Asset whereUsefulLifeInYears($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Asset withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Asset withoutTrashed()
 * @mixin \Eloquent
 */
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

    public function assetStatusLog(){
        return $this->hasMany(AssetStatusLog::class);
    }

    public function assetDisposalLogs(){
        return $this->hasMany(AssetDisposalLog::class);
    }

    public function workorders(){
        return $this->hasManyThrough(Workorder::class, Request::class, 'asset_id', 'request_id');
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
                $protected = ['disposed', 'maintenance', 'repair', 'fabrication'];

                if(!in_array($this->status->value, $protected) && $this->status->value !== AssetStatus::EXPIRED->value){
                    $oldStatus = $this->status->value;

                    $this->update(['status'=> AssetStatus::EXPIRED->value]);

                    AssetStatusLog::create([
                        'asset_id' => $this->id,
                        'changed_by' => null,
                        'from_status' => $oldStatus,
                        'to_status' => AssetStatus::EXPIRED->value,
                        'notes' => 'Asset status changed to expired due to reaching expiration date'
                    ]);

                    $this->refresh();
                }

                return 'expired';
            }
        }

        return $this->status->value;
    }

    protected static function booted(){
        static::created(function($asset){
            $asset->update([
                'asset_code' => 'AST-'. $asset->id
            ]);
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
                $q2->where('name', 'LIKE', "%{$search}%");
            })
            ->orWhereHas('category', function($q2) use ($search) {
                $q2->where('name', 'LIKE', "%{$search}%");
            });
        });
    }
}
