<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description'
    ];

    public function subCategories(){
        return $this->hasMany(SubCategory::class);
    }

    public function assets(){
        return $this->hasMany(Asset::class);
    }

    public function requests(){
        return $this->hasMany(Request::class);
    }

    // Local scope: define as scope""Name""() in model, call as name() in the controller
    // Example: scopeSearch($query, $term) → Category::search($term)
    public function scopeSearch($query, $search){
        if (!$search) return $query;

        return $query->where(function($q) use ($search) {
            $q->where('name', 'LIKE', "%{$search}%")
            ->orWhere('description', 'LIKE', "%{$search}%");
        });
    }
}
