<?php

namespace App\Models;

use App\Enums\DisposalMethods;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DisposalWorkorder extends Model
{
    use HasFactory;

    protected $fillable =  [
        "workorder_id",
        "asset_id",
        "disposal_method",
        "disposal_date",
        "reason"
    ];

    protected $casts = [
        "disposal_method" => DisposalMethods::class
    ];

    public function workorder(){
        return $this->belongsTo(Workorder::class);
    }

    public function asset(){
        return $this->belongsTo(Asset::class);
    }
}
