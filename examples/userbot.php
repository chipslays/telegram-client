<?php

use Chipslays\Telegram\Client;
use danog\MadelineProto\Logger;

require_once __DIR__ . '/../vendor/autoload.php';

$client = new Client([
    'session' => 'user.chipslays',
    'path' => [
        'sessions' => __DIR__ . '/storage/sessions',
    ],
    'madeline' => [
        'settings' => [
            'app_info' => [
                'app_id' => '',
                'app_hash' => '',
            ],
            'logger' => [
                'logger_level' => 0,
            ],
        ],
        'parse_mode' => 'html',
    ],
]);

// Catch message where contains ".hello" and edit this message to "Hello World!"
// See more: https://github.com/chipslays/event
$client->on(['message.message' => '.hello'], function () use ($client) {
    if (!$client->fromMe()) {
        return;
    }

    $client->edit('Hello World!');
});

// Callback is executed on every new update
$client->handleUpdates(function ($update) {
    Logger::log($update->toArray());
});