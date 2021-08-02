<?php

use Chipslays\Telegram\Client;

require_once __DIR__ . '/vendor/autoload.php';

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

$result = $client->sendMessage('@chipslays', 'Thank you for PHP MadelineProto wrapper! <3');