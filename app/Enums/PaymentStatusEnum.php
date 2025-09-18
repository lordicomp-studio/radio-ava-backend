<?php


namespace App\Enums;


enum PaymentStatusEnum : string {
    case Pending = 'Pending';
    case Processing = 'Processing';
    case Completed = 'Completed';
    case Failed = 'Failed';

}
