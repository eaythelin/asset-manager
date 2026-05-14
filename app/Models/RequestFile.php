<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Request as RequestModel;

/**
 * @property int $id
 * @property int $request_id
 * @property string $file_path
 * @property string|null $file_type
 * @property string|null $original_name
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read RequestModel $request
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RequestFile newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RequestFile newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RequestFile query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RequestFile whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RequestFile whereFilePath($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RequestFile whereFileType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RequestFile whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RequestFile whereOriginalName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RequestFile whereRequestId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RequestFile whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class RequestFile extends Model
{
    use HasFactory;

    protected $fillable = [
        'request_id',
        'file_path',
        'file_type',
        'original_name'
    ];

    public function request(){
        return $this->belongsTo(RequestModel::class);
    }
}
