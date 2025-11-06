<?php

declare(strict_types=1);

use Mezzio\Router\FastRouteRouter;

return [
    'router' => [
        'fastroute' => [
            FastRouteRouter::CONFIG_CACHE_ENABLED => true,
            FastRouteRouter::CONFIG_CACHE_FILE => 'cache/fastroute.php.cache',
        ],
    ],
];
