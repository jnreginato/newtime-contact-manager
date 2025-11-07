<?php

declare(strict_types=1);

use App\Adapter\Api\V1\Handler\CreateContactHandler;
use Mezzio\Application;

const API_V1 = '/api/v1';
const CONTACTS = '/contacts';

return static function (Application $app): void {
    $app->post(API_V1 . CONTACTS, ['CreateContactMiddleware', CreateContactHandler::class]);
};
