<?php

namespace App\Enums;

enum PriorityLevel:string
{
    case LOW = "low";
    case MEDIUM = "medium";
    case HIGH = "high";

    public function label():string
    {
        return match($this) {
            self::LOW => "Low",
            self::MEDIUM => "Medium",
            self::HIGH => "High",
        };
    }

    public function badgeClass():string
    {
        return match($this) {
            self::LOW => "badge-info",
            self::MEDIUM => "badge-warning",
            self::HIGH => "badge-warning",
        };
    }
}
