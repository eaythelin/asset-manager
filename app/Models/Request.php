<?php

namespace App\Models;

use App\Enums\DisposalConditions;
use App\Enums\RequestStatus;
use App\Enums\RequestTypes;
use App\Enums\ServiceTypes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property string $request_code
 * @property string|null $description
 * @property \Illuminate\Support\Carbon $date_requested
 * @property string|null $date_approved
 * @property string|null $asset_name
 * @property int $quantity
 * @property int $is_new_asset
 * @property int $requested_by
 * @property int|null $category_id
 * @property int|null $sub_category_id
 * @property int|null $handled_by
 * @property int|null $asset_id
 * @property int|null $department_id
 * @property RequestTypes $type
 * @property ServiceTypes|null $service_type
 * @property DisposalConditions|null $condition
 * @property RequestStatus $status
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\User|null $approvedBy
 * @property-read \App\Models\Asset|null $asset
 * @property-read \App\Models\Category|null $category
 * @property-read \App\Models\Department|null $department
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\RequestFile> $files
 * @property-read int|null $files_count
 * @property-read \App\Models\User|null $requestedBy
 * @property-read \App\Models\SubCategory|null $subCategory
 * @property-read \App\Models\Workorder|null $workorder
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Request newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Request newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Request query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Request search($search)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Request whereAssetId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Request whereAssetName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Request whereCategoryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Request whereCondition($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Request whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Request whereDateApproved($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Request whereDateRequested($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Request whereDepartmentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Request whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Request whereHandledBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Request whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Request whereIsNewAsset($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Request whereQuantity($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Request whereRequestCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Request whereRequestedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Request whereServiceType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Request whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Request whereSubCategoryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Request whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Request whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Request extends Model
{
    use HasFactory;
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
