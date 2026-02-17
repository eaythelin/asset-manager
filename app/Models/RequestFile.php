<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Request as RequestModel;

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
