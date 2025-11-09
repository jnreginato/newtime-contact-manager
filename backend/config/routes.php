<?php

declare(strict_types=1);

use App\Adapter\Api\V1\Handler\CreateContactHandler;
use App\Adapter\Api\V1\Handler\ListContactsHandler;
use App\Adapter\Api\V1\Handler\ReadContactHandler;
use App\Adapter\Api\V1\Handler\UpdateContactHandler;
use Mezzio\Application;

const NUMERIC_ID = '/{id:\d+}';
const API_V1 = '/api/v1';
const CONTACTS = '/contacts';

return static function (Application $app): void {
    $app->get(API_V1 . CONTACTS, ['ListContactsMiddleware', ListContactsHandler::class]);
    $app->get(API_V1 . CONTACTS . NUMERIC_ID, ['ReadContactMiddleware', ReadContactHandler::class]);
    $app->post(API_V1 . CONTACTS, ['CreateContactMiddleware', CreateContactHandler::class]);
    $app->patch(API_V1 . CONTACTS . NUMERIC_ID, ['UpdateContactMiddleware', UpdateContactHandler::class]);
};
