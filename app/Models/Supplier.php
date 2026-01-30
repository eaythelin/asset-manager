<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'contact_person',
        'email',
        'phone_number',
        'address'
    ];

    public function assets(){
        return $this->hasMany(Asset::class);
    }

    public function acquistionWorkOrders(){
        return $this->hasMany(AcquisitionWorkorder::class);
    }

    public function scopeSearch($query, $search){
        if (!$search) return $query;

        return $query->where(function($q) use ($search) {
            $q->where('name', 'LIKE', "%{$search}%")
            ->orWhere('contact_person', 'LIKE', "%{$search}%")
            ->orWhere('email', 'LIKE', "%{$search}%")
            ->orWhere('phone_number', 'LIKE', "%{$search}%")
            ->orWhere('address', 'LIKE', "%{$search}%");
        });
    }
}
