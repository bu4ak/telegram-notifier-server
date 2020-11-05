<?php

namespace App\Controller;

use App\Service\Telegram\TelegramServiceInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MessageController extends AbstractController
{
    /**
     * @Route("/message", name="message_send", methods={"POST"})
     */
    public function create(Request $request, TelegramServiceInterface $telegramService): Response
    {
        $token = $request->get('token', false);
        $message = $request->get('message', '[empty]');

        if ($token && $telegramService->handleMessage($token, $message)) {
            return $this->json(['message' => 'Ok.',]);
        }

        return $this->json(['message' => 'Invalid token.',], 400);
    }
}
