<?php

namespace App\Enums;

enum ReportType:string
{
    case ASSET = 'asset';
    case DEPRECIATION = 'depreciation';
    case DISPOSAL = 'disposal';

    public function label():string
    {
        return match($this){
            self::ASSET => 'Asset',
            self::DEPRECIATION => 'Depreciation',
            self::DISPOSAL => 'Disposal',
        };
    }

    public function badgeClass(): string
    {
        return match($this){
            self::ASSET       => "badge-info",
            self::DEPRECIATION => "badge-warning",
            self::DISPOSAL => "badge-error",
        };
    }
  }
