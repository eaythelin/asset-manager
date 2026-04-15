<?php

namespace App\Models;

use App\Enums\ReportType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    use HasFactory;

    protected $fillable =[
        'report_code',
        'report_type',
        'generated_by',
        'file_path'
    ];

    protected $casts = [
        "report_type" => ReportType::class,
    ];

    public function generatedBy(){
        return $this->belongsTo(User::class, 'generated_by');
    }
}
