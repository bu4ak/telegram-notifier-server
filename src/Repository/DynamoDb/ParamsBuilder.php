<?php

namespace App\Repository\DynamoDb;

use App\Entity\User;

class ParamsBuilder
{
    protected string $table;

    public function __construct(string $table)
    {
        $this->table = $table;
    }

    public function getCreateOrUpdateParams(User $user): array
    {
        return [
            'TableName' => $this->table,
            'Item' => [
                'chat_id' => [
                    'N' => (string)$user->getChatId()
                ],
                'token' => [
                    'S' => $user->getToken()
                ],
            ]
        ];
    }

    public function getFindByTokenParams(string $token): array
    {
        return [
            'TableName' => $this->table,
            'Key' => [
                'token' => [
                    'S' => $token
                ]
            ]
        ];
    }

    public function getFindByChatIdParams(int $chatId): array
    {
        return [
            'TableName' => $this->table,
            'ProjectionExpression' => "#t, #chat",
            'FilterExpression' => '#chat = :chat_id',
            'ExpressionAttributeNames' => ['#chat' => 'chat_id', '#t' => 'token'],
            'ExpressionAttributeValues' => [
                ':chat_id' => [
                    'N' => (string)$chatId
                ]
            ]
        ];
    }
}
