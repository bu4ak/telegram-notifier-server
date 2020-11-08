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
    protected Marshaler $marshaller;
    protected DynamoDbClient $dynamodb;
    protected Config $config;
    protected LoggerInterface $logger;
    protected ParamsBuilder $paramsBuilder;

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
        $this->marshaller = new Marshaler();
        $this->config = $config;
        $this->paramsBuilder = new ParamsBuilder($config->getTable());
    }

    public function findOneByToken(string $token): ?User
    {
        try {
            $params = $this->paramsBuilder->getFindByTokenParams($token);
            $result = $this->dynamodb->getItem($params);
            if ($item = $result->get('Item')) {
                $data = $this->marshaller->unmarshalItem($item);
                return new User($data['chat_id'], $data['token']);
            }
        } catch (DynamoDbException $e) {
            $this->logger->error($e->getMessage(), $e->getTrace());
        }
        return null;
    }

    public function findOneByChatId(int $chatId): ?User
    {
        try {
            $params = $this->paramsBuilder->getFindByChatIdParams($chatId);
            $result = $this->dynamodb->scan($params);

            if ($items = $result->get('Items')) {
                $data = $this->marshaller->unmarshalItem($items[0]);
                return new User($data['chat_id'], $data['token']);
            }
        } catch (DynamoDbException $e) {
            $this->logger->error($e->getMessage(), $e->getTrace());
        }
        return null;
    }

    public function save(User $user): bool
    {
        try {
            $params = $this->paramsBuilder->getCreateOrUpdateParams($user);
            $this->dynamodb->putItem($params);
            return true;
        } catch (DynamoDbException $e) {
            $this->logger->error($e->getMessage(), $e->getTrace());
        }
        return false;
    }
}
