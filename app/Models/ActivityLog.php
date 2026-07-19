<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Enums\ActivityAction;
use App\Enums\ActivityModule;

class ActivityLog extends Model
{
    protected $fillable = [
        'user_id',
        'module',
        'action',
        'description'
    ];

    protected $casts = [
        'module' => ActivityModule::class,
        'action' => ActivityAction::class
    ];

    public function user(){
        return $this->belongsTo(User::class)->withTrashed();
    }

    public static function log($module, $action, $description){
        static::create([
            'user_id' => auth()->id(),
            'module' => $module->value,
            'action' => $action->value,
            'description' => $description
        ]);
    }

    public function scopeSearch($query, $search){
        if(!$search) return $query;

        return $query->where(function($q) use ($search){
            $q->whereRaw("DATE_FORMAT(created_at, '%b %d, %Y %h:%i %p') LIKE ?", ["%{$search}%"])
            ->orWhereRaw("DATE_FORMAT(created_at, '%M %d, %Y %h:%i %p') LIKE ?", ["%{$search}%"])
            ->orWhereHas('user', function($q2) use ($search) {
                $q2->where('name', 'LIKE', "%{$search}%");
            })
            ->orWhere('module', 'LIKE', "%{$search}%")
            ->orWhere('action', 'LIKE', "%{$search}%")
            ->orWhere('description', 'LIKE', "%{$search}%");
        });
    }
}
