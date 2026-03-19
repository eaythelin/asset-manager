<?php

namespace App\Enums;

enum MaintenanceType:string
{
    case IN_HOUSE = "in_house";
    case SUBCONTRACTOR = "subcontractor";

    public function label():string
    {
        return match($this){
            self::IN_HOUSE => "In House",
            self::SUBCONTRACTOR => "Subcontractor"
        };
    }
}
