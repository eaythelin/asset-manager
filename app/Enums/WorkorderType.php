<?php

namespace App\Enums;

enum WorkorderType : string
{
    case SUBCONTRACTOR = "subcontractor";
    case IN_HOUSE = "in_house";

    public function label():string
    {
        return match($this) {
            self::SUBCONTRACTOR => "Subcontractor",
            self::IN_HOUSE => "In House",
        };
    }
}
