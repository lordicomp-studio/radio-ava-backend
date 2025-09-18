<?php

namespace App\Enums;

enum ChatTypeEnum : string
{
    case Support = 'Support';
    case Seller = 'Seller';
    case Bot = 'Bot';
    case Direct = 'Direct';
}
