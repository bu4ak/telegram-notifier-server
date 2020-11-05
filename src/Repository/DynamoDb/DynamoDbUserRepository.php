<?php

namespace App\Repository\DynamoDb;

use App\Entity\User;
use App\Repository\UserRepositoryInterface;
use Aws\DynamoDb\DynamoDbClient;
use Aws\DynamoDb\Exception\DynamoDbException;
use Aws\DynamoDb\Marshaler;
use Aws\Sdk;
use Psr\Log\LoggerInterface;

class DynamoDbUserRepository implements UserRepositoryInterface
{
    protected Marshaler $marshaler;
    protected DynamoDbClient $dynamodb;
    protected Config $config;
    protected LoggerInterface $logger;

    public function __construct(Config $config, LoggerInterface $logger)
    {
        $sdk = new Sdk(
            [
                'key' => $config->getKey(),
                'secret' => $config->getSecret(),
                'region' => $config->getRegion(),
                'version' => $config->getVersion()
            ]
        );

        $this->dynamodb = $sdk->createDynamoDb();
        $this->marshaler = new Marshaler();
        $this->config = $config;
        $this->logger = $logger;
    }

    public function findOneByToken(string $token): ?User
    {
        $params = [
            'TableName' => $this->config->getTable(),
            'Key' => [
                'token' => [
                    'S' => $token
                ]
            ]
        ];
        try {
            $result = $this->dynamodb->getItem($params);
            if ($item = $result->get('Item')) {
                $data = $this->marshaler->unmarshalItem($item);
                return new User($data['chat_id'], $data['token']);
            }
        } catch (DynamoDbException $e) {
            $this->logger->error($e->getMessage(), $e->getTrace());
        }
        return null;
    }

    public function findOneByChatId(int $chatId): ?User
    {
        $params = [
            'TableName' => $this->config->getTable(),
            'ProjectionExpression' =>  "#t, #chat",
            'FilterExpression' => '#chat = :chat_id',
            'ExpressionAttributeNames' => ['#chat' => 'chat_id', '#t' => 'token'],
            'ExpressionAttributeValues' => $this->marshaler->marshalJson("{\":chat_id\": $chatId}")
        ];

        try {
            $result = $this->dynamodb->scan($params);

            if ($items = $result->get('Items')) {
                $data = $this->marshaler->unmarshalItem($items[0]);
                return new User($data['chat_id'], $data['token']);
            }
        } catch (DynamoDbException $e) {
            $this->logger->error($e->getMessage(), $e->getTrace());
        }
        return null;
    }

    public function save(User $user): bool
    {
        $params = [
            'TableName' => $this->config->getTable(),
            'Item' => [
                'chat_id' => [
                    'N' => (string)$user->getChatId()
                ],
                'token' => [
                    'S' => $user->getToken()
                ],
            ]
        ];

        try {
            $this->dynamodb->putItem($params);
            return true;
        } catch (DynamoDbException $e) {
            $this->logger->error($e->getMessage(), $e->getTrace());
        }
        return false;
    }
}
