# WIP: PHP Telegram Client

Simple & scalable PHP wrapper over MadelineProto for easy work with Telegram Client API.

> 👷 Work in progress...

# Installation

```bash
composer require chipslays/telegram-client
```

# Usage

Run this script in terminal like `php client.php`.

The first time authorization will take a long time, but the next times it will be faster.

> Event system based on [`chipslays/event`](https://github.com/chipslays/event), see this for more cases.

```php
// client.php

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
                'app_id' => '###',
                'app_hash' => '######',
            ],
            'logger' => [
                'logger_level' => 0,
            ],
        ],
        'parse_mode' => 'html',
    ],
]);

$result = $client->sendMessage('@chipslays', 'Thank you for PHP MadelineProto wrapper! <3');
```

Simple userbot.

```php
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
                'app_id' => '###',
                'app_hash' => '######',
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

$client->on(['message.message' => '.bday {name}'], function ($name) use ($client) {
    if (!$client->fromMe()) {
        return;
    }

    $client->edit("Happy Birthday, {$name}! 🎉🎂");
});

// Start polling Telegram updates. 
$client->handleUpdates(function ($update) {
    // This executed on every new update.
    Logger::log($update->toArray());
});
```

More soon...
