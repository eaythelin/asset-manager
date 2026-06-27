<?php

namespace App\Enums;

enum ActivityAction : string
{
    case CREATED = "created";
    case UPDATED = "updated";
    case DELETED = "deleted";
    case DISPOSED = "disposed";
    case APPROVED = "approved";
    case REJECTED = 'rejected';
    case COMPLETED = 'completed';
    case CANCELLED = 'cancelled';
    case STARTED = 'started';
    case LOGIN = 'login';
    case ARCHIVED = 'archived';
    case RESTORED = 'restored';

    public function label():string
    {
        return match($this){
            self::CREATED => "Created",
            self::UPDATED => "Updated",
            self::DELETED => "Deleted",
            self::DISPOSED => "Disposed",
            self::APPROVED => "Approved",
            self::REJECTED => "Rejected",
            self::COMPLETED => "Completed",
            self::CANCELLED => "Cancelled",
            self::STARTED => "Started",
            self::LOGIN => "Login",
            self::ARCHIVED => "Archived",
            self::RESTORED => "Restored"
        };
    }
}
