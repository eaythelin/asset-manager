<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Enums\DisposalMethods;

class AssetDisposalLog extends Model
{

    use HasFactory;
    protected $fillable = [
        'asset_id',
        'disposal_method',
        'disposal_date',
        'quantity',
        'reason'
    ];

    protected $casts = [
        'disposal_date' => 'date',
        'disposal_method' => DisposalMethods::class
    ];

    public function asset(){
        return $this->belongsTo(Asset::class);
    }
}
