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
        return $this->belongsTo(User::class, 'user_id');
    }

    public static function log($module, $action, $description){
        static::create([
            'user_id' => auth()->id(),
            'module' => $module->value,
            'action' => $action->value,
            'description' => $description
        ]);
    }
}
