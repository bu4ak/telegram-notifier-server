<?php

namespace App\MessageHandler;

use App\Message\Message;
use App\Service\Telegram\TelegramServiceInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

class MessageHandler implements MessageHandlerInterface
{
    protected TelegramServiceInterface $telegramService;
    protected LoggerInterface $logger;

    public function __construct(TelegramServiceInterface $telegramService)
    {
        $this->telegramService = $telegramService;
    }

    public function __invoke(Message $message)
    {
        $this->telegramService->sendMessageRequest($message->getChatId(), $message->getMessage());
    }
}
