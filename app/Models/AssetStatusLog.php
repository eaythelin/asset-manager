<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AssetStatusLog extends Model
{
    protected $fillable = [
        "asset_id",
        "changed_by",
        "from_status",
        "to_status",
        "notes",
    ];

    public function asset(){
        return $this->belongsTo(Asset::class);
    }

    public function changedBy(){
        return $this->belongsTo(User::class, 'changed_by');
    }
}
