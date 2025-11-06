<?php

declare(strict_types=1);

define('START_EXECUTION_TIME', microtime(true));
define('RESOURCE_USAGE', getrusage() ?: []);
define('APP_ROOT', dirname((string) realpath(__DIR__)));
