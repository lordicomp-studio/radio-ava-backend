<?php


namespace App\Enums;


use App\Traits\EnumHasOptions;

enum UserLevelEnum : string {
    use EnumHasOptions;

    case Client = 'Client';
    case Seller = 'Seller';
    case Admin = 'Admin';
}
