<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * @property string $first_name
 * @property string $last_name
 * @property string $id
 */

class Employee extends Model
{
    use HasFactory;
    protected $fillable = [
        'first_name',
        'last_name',
        'department_id',
    ];

    //accessor for full_name attribute, laravel converts getFullNameAttribute -> full_name
    public function getFullNameAttribute():string {
        return "{$this->first_name} {$this->last_name}";
    }
    
    //Employee can only have one Department
    public function department(){
        return $this->belongsTo(Department::class);
    }

    // Optional: employee may have a user account
    public function user(){
        return $this->hasOne(User::class);
    }

    //one employee can have many assets
    public function assets(){
        return $this->hasMany(Asset::class, 'custodian_id');
    }

    //query!
    public function scopeSearch($query, $search){
        if (!$search) return $query;

        return $query->where(function($q) use ($search) {
            $q->where('first_name', 'LIKE', "%{$search}%")
            ->orWhere('last_name', 'LIKE', "%{$search}%")
            ->orWhereRaw("CONCAT(first_name,' ',last_name) LIKE ?", ["%{$search}%"])
            ->orWhereHas('department', function($q2) use ($search) {
                $q2->where('name', 'LIKE', "%{$search}%");
            });
        });
    }
}
