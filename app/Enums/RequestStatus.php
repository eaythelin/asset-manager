<?php

namespace App\Enums;

enum RequestStatus:string
{
    case PENDING = "pending";
    case REJECTED = "rejected";
    case APPROVED = "approved";
    case CANCELLED = "cancelled";


    public function label():string
    {
        return match($this) {
            self::PENDING => "Pending",
            self::REJECTED => "Rejected",
            self::APPROVED => "Approved",
            self::CANCELLED => "Cancelled"
        };
    }

    public function badgeClass():string
    {
        return match($this){
            self::PENDING => "badge-warning",
            self::REJECTED => "badge-error",
            self::APPROVED => "badge-success",
            self::CANCELLED => "badge-error"
        };
    }
}
