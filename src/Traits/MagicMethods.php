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
        try {
            $this->sendMessage($chat, '/start');
        } catch (\Throwable $th) {
            return false;
        }

        usleep(round($waitSeconds * 1000000));

        $response = $this->getMessageHistory($chat, 1);

        return !$response->get('messages.0.out', true);
    }

    public function fromMe()
    {
        return $this->update('message.out', false);
    }

    public function fromChannel()
    {
        return $this->update('_') == 'updateNewChannelMessage';
    }

    public function fromChat()
    {
        return $this->update('_') == 'updateNewMessage';
    }

    /**
     * Edit incoming message.
     *
     * @param string $text
     * @param array $extra
     * @return void
     */
    public function edit(string $text, array $extra = [])
    {
        $this->getMadeline()->messages->editMessage(array_merge([
            'id' => $this->update('message.id'),
            'peer' => $this->update->toArray(),
            'message' => $text,
            'parse_mode' => $this->config('madeline.parse_mode', 'html'),
        ], []));
    }
}