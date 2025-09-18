<?php

namespace App\Helpers\TestingAndDev;

class TddHelper
{
    public static array $productWidgetResourceFields = [
        'id', 'name', 'slug', 'description', 'price', 'code', 'version','status',
        'payments_count', 'satisfaction_percent', 'views_count', 'coverUrl', 'rate', 'published_at',
        'category' => ['id', 'name', 'slug']
    ];

    public static array $articleWidgetResourceFields =                 [
        'id', 'title', 'slug', 'description', 'body', 'views_count', 'coverUrl', 'created_at',
        'category' => ['id', 'name', 'slug']
    ];

}
