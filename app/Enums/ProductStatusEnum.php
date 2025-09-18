<?php


namespace App\Enums;


use App\Traits\EnumHasOptions;

enum ProductStatusEnum : string {
    use EnumHasOptions;

    case Unchecked = 'Unchecked';
    case Pending = 'Pending';
    case Confirmed = 'Confirmed';
    case Rejected = 'Rejected';
    case Suspended = 'Suspended';
}
