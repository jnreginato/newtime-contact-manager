<?php

declare(strict_types=1);

use Laminas\Diactoros\Response\HtmlResponse;
use Mezzio\Application;

return static function (Application $app): void {
    $app->get('/', function () {
        return new HtmlResponse('<h1>Hello, world!</h1>');
    });
};
