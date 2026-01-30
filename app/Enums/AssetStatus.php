<?php

namespace App\Enums;

enum AssetStatus:string
{
    case ACTIVE = 'active';
    case DISPOSED = 'disposed';
    case UNDER_SERVICE = 'under_service'; // maintenance

    public function label(): string
    {
        return match($this) {
            self::ACTIVE => 'Active',
            self::DISPOSED => 'Disposed',
            self::UNDER_SERVICE => 'Under Service',
        };
    }
}
