<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubCategory extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'description',
        'category_id'
    ];

    public function category(){
        return $this->belongsTo(Category::class);
    }

    public function assets(){
        return $this->hasMany(Asset::class);
    }

    public function requests(){
        return $this->hasMany(Request::class);
    }

    public function scopeSearch($query, $search){
        if (!$search) return $query;

        return $query->where(function($q) use ($search) {
            $q->where('name', 'LIKE', "%{$search}%")
            ->orWhere('description', 'LIKE', "%{$search}%")
            ->orWhereHas('category', function($q2) use ($search) {
                $q2->where('name', 'LIKE', "%{$search}%");
            });
        });
    }
}
