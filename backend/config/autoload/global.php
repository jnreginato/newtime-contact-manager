<?php

declare(strict_types=1);

use Laminas\ConfigAggregator\ConfigAggregator;

return [
    'debug' => false,
    'environment' => getenv('APPLICATION_ENV'),
    'is_development' => getenv('APPLICATION_ENV') === 'development',
    'mezzio' => [
        'error_handler' => [
            'template_404' => 'error::404',
            'template_error' => 'error::error',
        ],
    ],
    ConfigAggregator::ENABLE_CACHE => true,
];
