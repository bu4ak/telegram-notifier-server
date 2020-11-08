<?php

namespace App\Service\Http;

use Psr\Log\LoggerInterface;

class HttpService implements HttpServiceInterface
{
    protected LoggerInterface $logger;

    public function get(string $url)
    {
        $ch = curl_init();
        curl_setopt(
            $ch,
            CURLOPT_URL,
            $url
        );
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $result = curl_exec($ch);
        if ($result === false) {
            $this->logger->error(curl_error($ch));
        }
        curl_close($ch);
    }
}
