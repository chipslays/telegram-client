<?php

namespace Chipslays\Telegram;

use Chipslays\Arr\Arr;
use Chipslays\Collection\Collection;
use Chipslays\Telegram\Traits\Methods;
use danog\MadelineProto\API;

class Client
{
    use Methods;

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