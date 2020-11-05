<?php

namespace App\Message;

class Message
{
    private int $chatId;
    private string $message;
    private string $timestamp;

    public function __construct(int $chatId, string $message)
    {
        $this->chatId = $chatId;
        $this->message = $message;
        $this->timestamp = microtime();
    }

    /**
     * @return int
     */
    public function getChatId(): int
    {
        return $this->chatId;
    }

    /**
     * @return string
     */
    public function getMessage(): string
    {
        return $this->message;
    }
}
