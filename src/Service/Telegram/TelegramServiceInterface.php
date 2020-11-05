<?php

namespace App\Service\Telegram;

interface TelegramServiceInterface
{
    public function sendMessageRequest(int $chatId, string $message): bool;

    public function handleWebhook(string $json): bool;

    public function handleMessage(string $token, string $message): bool;
}
