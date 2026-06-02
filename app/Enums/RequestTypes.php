<?php

namespace App\Enums;

enum RequestTypes:string
{
    case MAINTENANCE = "maintenance";
    case REPAIR = "repair";
    case FABRICATION = "fabrication";

    public function label():string
    {
        return match($this) {
            self::MAINTENANCE => "Maintenance",
            self::REPAIR => "Repair",
            self::FABRICATION => "Fabrication",
        };
    }

    public function badgeColor():string
    {
        return match($this) {
            self::MAINTENANCE => 'badge-info',
            self::REPAIR => 'badge-info',
            self::FABRICATION => 'badge-info',
            default         => 'bg-gray-500',
        };
    }
}
