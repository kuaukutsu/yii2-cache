<?php

declare(strict_types=1);

use yii\caching\ArrayCache;
use yii\caching\CacheInterface;

return [
    'singletons' => [
        CacheInterface::class => ArrayCache::class,
    ]
];
