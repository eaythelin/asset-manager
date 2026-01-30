<?php

namespace App\Enums;

enum RequestTypes:string
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
