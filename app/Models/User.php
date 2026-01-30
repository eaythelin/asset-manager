<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property string $is_active
 */

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasRoles, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'employee_id',
        'is_active',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
    
    // Each user must belong to one employee
    public function employee(){
        return $this->belongsTo(Employee::class);
    }

    public function requestedRequests(){
        return $this->hasMany(Request::class, 'requested_by');
    }

    public function approvedRequests(){
        return $this->hasMany(Request::class, 'approved_by');
    }

    public function scopeSearch($query, $search){
        if (!$search) return $query;

        return $query->where(function($q) use ($search){
            $q->where('name', 'LIKE', "%{$search}%")
            ->orWhere('email', 'LIKE', "%{$search}%")
            ->orWhereHas('roles', function($roleQuery) use ($search){
                $roleQuery->where('name', 'LIKE', "%{$search}%");
            });
        });
    }

    public function getInitials(){
        $name = $this->name ?? '';
        $parts = array_filter(explode(' ', trim($name)));
        $selected = array_slice($parts, 0, 2);

        return implode('', array_map(fn($p)=>strtoupper($p[0]), $selected));
    }
}
