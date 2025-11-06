<?php

declare(strict_types=1);

use Mezzio\Application;

return static function (Application $app): void {
    $app->pipe(Laminas\Stratigility\Middleware\ErrorHandler::class);
    $app->pipe(Mezzio\Helper\ServerUrlMiddleware::class);
    $app->pipe(Mezzio\Helper\BodyParams\BodyParamsMiddleware::class);
    $app->pipe(Mezzio\Router\Middleware\RouteMiddleware::class);
    $app->pipe(Mezzio\Router\Middleware\ImplicitHeadMiddleware::class);
    $app->pipe(Mezzio\Router\Middleware\ImplicitOptionsMiddleware::class);
    $app->pipe(Mezzio\Router\Middleware\MethodNotAllowedMiddleware::class);
    $app->pipe(Mezzio\Router\Middleware\DispatchMiddleware::class);
    $app->pipe(Mezzio\Handler\NotFoundHandler::class);
};
