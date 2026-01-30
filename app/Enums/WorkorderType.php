<?php

namespace App\Enums;

enum WorkorderType:string
{
    case ACQUISITION = "acquisition";
    case SERVICE = "service";
    case DISPOSAL = "disposal";

    public function label():string
    {
        return match($this) {
            self::ACQUISITION => "Acquisition",
            self::SERVICE => "Service",
            self::DISPOSAL => "Disposal",
        };
    }
}
