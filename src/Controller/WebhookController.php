<?php

namespace App\Controller;

use App\Service\Telegram\TelegramServiceInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class WebhookController extends AbstractController
{
    /**
     * @Route("/webhook", name="webhook")
     */
    public function update(Request $request, TelegramServiceInterface $telegramService): Response
    {
        $telegramService->handleWebhook($request->getContent());

        return $this->json(
            [
                'message' => 'Ok.',
            ]
        );
    }
}
