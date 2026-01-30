<?php

namespace App\Enums;

enum DisposalMethods: string
{
    case SOLD = "sold";
    case DONATED = "donated";
    case SCRAPPED = "scrapped";
    case RECYCLED = "recycled";
    case DESTROYED = "destroyed";

    public function label():string
    {
        return match($this){
            self::SOLD => "Sold",
            self::DONATED => "Donated",
            self::SCRAPPED => "Scrapped",
            self::RECYCLED => "Recycled",
            self::DESTROYED => "Destroyed"
        };
    }
}
