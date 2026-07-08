<?php

namespace App\Enums;

enum ActivityModule : string
{
    case ASSET = "asset";
    case REQUEST = "request";
    case WORKORDER = "workorder";
    case USER = "user";
    case EMPLOYEE = "employee";
    case DEPARTMENT = "department";
    case CATEGORY = "category";
    case SUBCATEGORY = "subcategory";
    case SUPPLIER = "supplier";

    public function label():string
    {
        return match($this) {
            self::ASSET => 'Asset',
            self::REQUEST => 'Request',
            self::WORKORDER => 'Work Order',
            self::USER => 'User',
            self::EMPLOYEE => 'Employee',
            self::DEPARTMENT => 'Department',
            self::CATEGORY => 'Category',
            self::SUBCATEGORY => 'Subcategory',
            self::SUPPLIER => 'Supplier'
        };
    }
}
