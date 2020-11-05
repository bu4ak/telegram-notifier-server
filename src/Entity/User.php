<?php

namespace App\Entity;

class User
{
    private int $chatId;
    private string $token;

    public function __construct(int $chatId, ?string $token = null)
    {
        $this->chatId = $chatId;
        $this->token = $token ?? sha1(uniqid(microtime(), true));
    }

    public function getChatId(): ?int
    {
        return $this->chatId;
    }

    public function getToken(): ?string
    {
        return $this->token;
    }
}
