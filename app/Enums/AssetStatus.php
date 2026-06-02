<?php

namespace App\Enums;

enum AssetStatus:string
{
    case ACTIVE = 'active';
    case DISPOSED = 'disposed';
    case MAINTENANCE = 'maintenance';
    case EXPIRED = 'expired';
    case REPAIR = 'repair';
    case FABRICATION = 'fabrication';

    public function label(): string
    {
        return match($this) {
            self::ACTIVE => 'Active',
            self::DISPOSED => 'Disposed',
            self::MAINTENANCE => 'Maintenance',
            self::REPAIR => 'Repair',
            self::FABRICATION => 'Fabrication',
            self::EXPIRED => 'Expired',
        };
    }

    public function badgeColor():string
    {
        return match($this) {
            self::ACTIVE        => 'badge-success',
            self::EXPIRED       => 'badge-warning',
            self::DISPOSED      => 'badge-error',
            self::MAINTENANCE => 'badge-info',
            self::REPAIR => 'badge-info',
            self::FABRICATION => 'badge-info',
            default         => 'bg-gray-500',
        };
    }
}
