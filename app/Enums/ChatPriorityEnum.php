<?php

namespace App\Enums;

use App\Traits\EnumHasOptions;

enum ChatPriorityEnum : string
{
    use EnumHasOptions;

    case Low = 'Low';
    case Medium = 'Medium';
    case High = 'High';
}
