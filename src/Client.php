<?php

namespace Chipslays\Telegram;

use Chipslays\Telegram\Traits\ApiMethods;
use Chipslays\Telegram\Traits\MagicMethods;
use Chipslays\Collection\Collection;
use Chipslays\Arr\Arr;
use Chipslays\Event\EventTrait;
use danog\MadelineProto\API;
use danog\MadelineProto\Logger;

class Client
{
    use ApiMethods;
    use MagicMethods;
    use EventTrait;

    /**
     * @var array
     */
    private $config = [];

    private $madeline;

    /**
     * @var Collection
     */
    private $update;

    public function __construct($config)
    {
        $this->config = $config;

        $sessionsPath = rtrim($this->config('path.sessions'), '\\/');
        $currentSession = "{$sessionsPath}/" . $this->config('session');

        $this->madeline = new API($currentSession, $this->config('madeline.settings', []));
        $this->madeline->async(false);

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
        return new Collection($response);
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

    public function update($key, $default = null)
    {
        return $this->update->get($key, $default);
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

    public function handleUpdates($callback = null, int $limit = 50, int $timeout = 0)
    {
        $offset = 0;

        while (true) {
            $updates = $this->getMadeline()->getUpdates([
                'offset' => $offset,
                'limit' => $limit,
                'timeout' => $timeout,
            ]);

            foreach ($updates as $update) {
                $this->update = new Collection($update['update']);
                $this->setEventData($this->update);

                $offset = $update['update_id'] + 1;

                $this->getMadeline()->setNoop();

                /** ignore everything except messages */
                if ($this->update('_') !== 'updateNewMessage' && $this->update('_') !== 'updateNewChannelMessage') {
                    continue;
                }

                /** ignore old updates if bot was down some time */
                if (round(time() - $this->update('message.date')) > 60) {
                    continue;
                }

                if ($callback) {
                    $this->executeCallback($callback, [$this->update]);
                }

                $this->run();
            }
        }
    }
}