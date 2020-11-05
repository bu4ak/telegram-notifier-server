<?php

namespace App\Service\Http;

interface HttpServiceInterface
{
    public function sendMessage(string $url);
}
