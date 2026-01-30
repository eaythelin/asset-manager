<?php

namespace App\Enums;

enum WorkorderStatus:string
{
    case PENDING = "pending";
    case IN_PROGRESS = "in_progress";
    case COMPLETED = "completed";

    case OVERDUE = "overdue";
    case CANCELLED = "cancelled";

    public function label():string
    {
        return match($this) {
            self::PENDING => "Pending",
            self::IN_PROGRESS => "In Progress",
            self::COMPLETED => "Completed",
            self::OVERDUE => "Overdue",
            self::CANCELLED => "Cancelled"
        };
    }

    public function badgeClass():string
    {
        return match($this){
            self::PENDING => "badge-warning",
            self::IN_PROGRESS => "badge-info",
            self::COMPLETED => "badge-success",
            self::OVERDUE => "badge-error",
            self::CANCELLED => "badge-ghost"
        };
    }
}
