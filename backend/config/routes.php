<?php

declare(strict_types=1);

use App\Adapter\Api\V1\Handler\CreateContactHandler;
use App\Adapter\Api\V1\Handler\ListContactsHandler;
use Mezzio\Application;

const API_V1 = '/api/v1';
const CONTACTS = '/contacts';

return static function (Application $app): void {
    $app->get(API_V1 . CONTACTS, ['ListContactsMiddleware', ListContactsHandler::class]);
    $app->post(API_V1 . CONTACTS, ['CreateContactMiddleware', CreateContactHandler::class]);
};
