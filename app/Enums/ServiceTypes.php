<?php

namespace App\Enums;

enum ServiceTypes:string
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
}
