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

    public function scopeSearch($query, $search){
        if (!$search) return $query;

        return $query->where(function($q) use ($search) {
            $q->where('report_code', 'LIKE', "%{$search}%")
            ->orWhere('report_type', 'LIKE', "%{$search}%")
            ->orWhereRaw("DATE_FORMAT(created_at, '%M %d, %Y') LIKE ?", ["%{$search}%"])

            ->orWhereHas('generatedBy', function($q2) use($search){
                $q2->where('name', 'LIKE', "%{$search}%");
            });
        });
    }
}
