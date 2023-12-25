<?php

use App\Controllers\AuthController;
use App\Controllers\MailController;
use App\Router;

Router::add('GET', 'oauth', [AuthController::class, 'oauth']);
Router::add('GET', 'callback', [AuthController::class, 'oauthCallback']);

Router::middleware(['auth'], function () {
    Router::add('GET', 'mails', [MailController::class, 'index']);
    Router::add('GET', 'mail', [MailController::class, 'show']);
    Router::add('POST', 'mails', [MailController::class, 'create']);
});
