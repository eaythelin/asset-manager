<?php

namespace App\Enums;

enum ReportType:string
{
    case ASSET = 'asset';
    case DEPRECIATION = 'depreciation';
    case WORKORDER = 'workorder';

    public function label():string
    {
        return match($this){
            self::ASSET => 'Asset',
            self::DEPRECIATION => 'Depreciation',
            self::WORKORDER => 'Workorder',
        };
    }

    public function badgeClass(): string
    {
        return match($this){
            self::ASSET       => "badge-info",
            self::DEPRECIATION => "badge-warning",
            self::WORKORDER     => "badge-success",
        };
    }
  }
