<?php

namespace App\Service\Telegram;

use App\Entity\User;
use App\Message\Message;
use App\Repository\UserRepositoryInterface;
use App\Service\Http\HttpServiceInterface;
use Symfony\Component\Messenger\MessageBusInterface;

class TelegramService implements TelegramServiceInterface
{
    public const PARS_MODE_MARKDOWN = 'MarkdownV2';

    protected string $token;
    protected HttpServiceInterface $httpService;
    protected UserRepositoryInterface $userRepository;
    protected MessageBusInterface $bus;

    public function __construct(
        string $token,
        HttpServiceInterface $httpService,
        UserRepositoryInterface $userRepository,
        MessageBusInterface $bus
    ) {
        $this->token = $token;
        $this->httpService = $httpService;
        $this->userRepository = $userRepository;
        $this->bus = $bus;
    }

    public function sendMessageRequest(int $chatId, string $message, ?string $parseMode = null): bool
    {
        $params = http_build_query(
            [
                'chat_id' => $chatId,
                'text' => $message,
                'parse_mode' => $parseMode,
            ]
        );
        $query = "https://api.telegram.org/bot{$this->token}/sendMessage?$params";
        $this->httpService->get($query);

        return true;
    }

    public function handleWebhook(string $json): bool
    {
        $data = json_decode($json, true);
        $chatId = (int)($data['message']['chat']['id'] ?? 0);

        if (!$user = $this->userRepository->findOneByChatId($chatId)) {
            $user = new User($chatId);
            $this->userRepository->save($user);
        }
        $message = sprintf('This channel token: `%s`', $user->getToken());

        return $this->sendMessageRequest($user->getChatId(), $message, self::PARS_MODE_MARKDOWN);
    }

    public function handleMessage(string $token, string $text): bool
    {
        $user = $this->userRepository->findOneByToken($token);

        if ($user === null) {
            return false;
        }

        $this->bus->dispatch(
            new Message($user->getChatId(), $text)
        );

        return true;
    }
}
