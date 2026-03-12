<?php

namespace App\Enums;

enum DisposalConditions: string
{
    case DAMAGED = "damaged";
    case OBSOLETE = "obsolete";
    case BEYOND_REPAIR = "beyond_repair";
    case END_OF_LIFE = "end_of_life";
    case LOST = "lost";
    case STOLEN = "stolen";

    public function label():string
    {
        return match($this){
            self::DAMAGED => "Damaged",
            self::OBSOLETE => "Obsolete",
            self::BEYOND_REPAIR => "Beyond Repair",
            self::END_OF_LIFE => "End of Life",
            self::LOST => "Lost",
            self::STOLEN => "Stolen",
        };
    }
}
