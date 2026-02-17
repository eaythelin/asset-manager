<?php

namespace App\Enums;

enum WorkorderType:string
{
    case REQUISITION = "requisition";
    case SERVICE = "service";
    case DISPOSAL = "disposal";

    public function label():string
    {
        return match($this) {
            self::REQUISITION => "Requisition",
            self::SERVICE => "Service",
            self::DISPOSAL => "Disposal",
        };
    }
}
