<?php

namespace App\Enums;

enum RequestStatus:string
{
    case PENDING = "pending";
    case DECLINE = "declined";
    case APPROVED = "approved";
    case CANCELLED = "cancelled";

    case DRAFT = "draft";

    public function label():string
    {
        return match($this) {
            self::PENDING => "Pending",
            self::DECLINE => "Declined",
            self::APPROVED => "Approved",
            self::CANCELLED => "Cancelled",
            self::DRAFT => "Draft"
        };
    }

    public function badgeClass():string
    {
        return match($this){
            self::PENDING => "badge-warning",
            self::DECLINE => "badge-error",
            self::APPROVED => "badge-success",
            self::CANCELLED => "badge-error",
            self::DRAFT => "badge-accent"
        };
    }
}
