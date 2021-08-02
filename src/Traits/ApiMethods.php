<?php

namespace Chipslays\Telegram\Traits;

trait ApiMethods
{
    /**
     * Sends a message to a chat.
     *
     * @see https://docs.madelineproto.xyz/API_docs/methods/messages.sendMessage.html
     *
     * @param mixed $chat https://docs.madelineproto.xyz/API_docs/types/InputPeer.html
     * @param string $text
     * @param array $extra Extra options
     * @return mixed
     */
    public function sendMessage($chat, $text, $extra = [])
    {
        $response = $this->getMadeline()->messages->sendMessage(array_merge([
            'peer' => $chat,
            'message' => $text,
            'parse_mode' => $this->config('madeline.parse_mode', 'html'),
        ], $extra));

        return $this->handleResponse($response);
    }

    /**
     * Gets back the conversation history with one interlocutor / within a chat
     *
     * @see https://docs.madelineproto.xyz/API_docs/methods/messages.getHistory.html
     *
     * @param mixed $chat https://docs.madelineproto.xyz/API_docs/types/InputPeer.html
     * @param integer $limit Number of results to return
     * @param integer $addOffset Number of list elements to be skipped, negative values are also accepted.
     * @param array $extra Extra options
     * @return mixed
     */
    public function getMessageHistory($chat, $limit = 20, $addOffset = 0, $extra = [])
    {
        $response = $this->getMadeline()->messages->getHistory(array_merge([
            'peer' => $chat,
            'offset_id' => 0,
            'offset_date' => 0,
            'add_offset' => $addOffset,
            'limit' => $limit,
            'max_id' => 2147483647,
            'min_id' => 0,
        ], $extra));

        return $this->handleResponse($response);
    }
}