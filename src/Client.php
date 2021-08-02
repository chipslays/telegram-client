<?php

namespace Chipslays\Telegram;

use Chipslays\Telegram\Traits\ApiMethods;
use Chipslays\Telegram\Traits\MagicMethods;
use Chipslays\Collection\Collection;
use Chipslays\Arr\Arr;
use danog\MadelineProto\API;
use danog\MadelineProto\Logger;

class Client
{
    use ApiMethods;
    use MagicMethods;

    /**
     * @var array
     */
    private $config = [];

    private $madeline;

    public function __construct($config)
    {
        $this->config = $config;

        $sessionsPath = rtrim($this->config('path.sessions'), '\\/');
        $currentSession = "{$sessionsPath}/" . $this->config('session');

        $this->madeline = new API($currentSession, $this->config('madeline.settings', []));

        $this->madeline->logger->colors[Logger::NOTICE] = \implode(';', [Logger::FOREGROUND['light_gray'], Logger::SET['bold'], Logger::BACKGROUND['magenta']]);
        $this->madeline->logger('👋 PHP Telegram Client');
        $this->madeline->logger('🔗 https://github.com/chipslays/telegram-client');
        $this->madeline->logger->colors[Logger::NOTICE] = \implode(';', [Logger::FOREGROUND['yellow'], Logger::SET['bold']]);

        $this->madeline->start();
    }

    /**
     * @param array $response
     * @return Collection
     */
    protected function handleResponse($response)
    {
        return collection($response);
    }

    /**
     * Get value from config.
     *
     * @param string|int $key
     * @param mixed $default
     * @return mixed
     */
    public function config($key, $default = null)
    {
        return Arr::get($this->config, $key, $default);
    }

    /**
     * Get MadelineProto instance.
     *
     * @return API
     */
    public function getMadeline()
    {
        return $this->madeline;
    }
}