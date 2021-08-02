<?php

namespace Chipslays\Telegram\Traits;

trait MagicMethods
{
    /**
     * Send message and wait answer from bot.
     *
     * @param mixed $chat https://docs.madelineproto.xyz/API_docs/types/InputPeer.html
     * @param int|float $waitTime How many seconds to wait
     * @return bool
     */
    public function isBotActive($chat, $waitSeconds = 3)
    {
        $this->sendMessage($chat, '/start');

        usleep(round($waitSeconds * 1000000));

        $response = $this->getMessageHistory($chat, 1);

        return !$response->get('messages.0.out', true);
    }
}