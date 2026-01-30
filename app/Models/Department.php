<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Department extends Model
{
    //
    use HasFactory;

    protected $fillable = [
        'name',
        'description'
    ];

    //One Department can have Many Employee
    public function employees(){
        return $this->hasMany(Employee::class);
    }

    public function assets(){
        return $this->hasMany(Asset::class);
    }

    public function scopeSearch($query, $search){
        if (!$search) return $query;

        return $query->where(function($q) use ($search) {
            $q->where('name', 'LIKE', "%{$search}%")
            ->orWhere('description', 'LIKE', "%{$search}%");
        });
    }
}
