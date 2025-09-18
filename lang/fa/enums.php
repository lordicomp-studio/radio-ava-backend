<?php

return [
    'App\Enums\UserLevelEnum' => [
        'Client' => 'مشتری',
        'Seller' => 'فروشنده',
        'Admin' => 'مدیر',
    ],
    'App\Enums\ChatPriorityEnum' => [
        'Low' => 'پایین',
        'Medium' => 'متوسط',
        'High' => 'بالا',
    ],
    'App\Enums\ProductStatusEnum' => [
        'Unchecked' => 'چک نشده',
        'Pending' => 'در حال بررسی',
        'Confirmed' => 'تایید شده',
        'Rejected' => 'رد شده',
        'Suspended' => 'تعلیق شده',
    ],
    'App\Enums\ProductEditRequestStatusEnum' => [
        'Pending' => 'در حال بررسی',
        'Confirmed' => 'تایید شده',
        'Rejected' => 'رد شده',
    ],
];
