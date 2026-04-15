<?php

namespace App\Enums;

enum AssetStatus:string
{
    case ACTIVE = 'active';
    case DISPOSED = 'disposed';
    case UNDER_SERVICE = 'under_service'; // maintenance

    case EXPIRED = 'expired';

    public function label(): string
    {
        return match($this) {
            self::ACTIVE => 'Active',
            self::DISPOSED => 'Disposed',
            self::UNDER_SERVICE => 'Under Service',
            self::EXPIRED => 'Expired',
        };
    }

    public function badgeColor():string
    {
        return match($this) {
            self::ACTIVE        => 'badge-success',
            self::EXPIRED       => 'badge-warning',
            self::DISPOSED      => 'badge-error',
            self::UNDER_SERVICE => 'badge-info',
            default         => 'bg-gray-500',
        };
    }
}