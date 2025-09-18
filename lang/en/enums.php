<?php

return [
    'App\Enums\UserLevelEnum' => [
        'Client' => 'Client',
        'Seller' => 'Seller',
        'Admin' => 'Admin',
    ],
    'App\Enums\ChatPriorityEnum' => [
        'Low' => 'Low',
        'Medium' => 'Medium',
        'High' => 'High',
    ],
    'App\Enums\ProductStatusEnum' => [
        'Unchecked' => 'Unchecked',
        'Pending' => 'Pending',
        'Confirmed' => 'Confirmed',
        'Rejected' => 'Rejected',
        'Suspended' => 'Suspended',
    ],
    'App\Enums\ProductEditRequestStatusEnum' => [
        'Pending' => 'Pending',
        'Confirmed' => 'Confirmed',
        'Rejected' => 'Rejected',
    ],
];
