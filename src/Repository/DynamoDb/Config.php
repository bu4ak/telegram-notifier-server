<?php

namespace App\Repository\DynamoDb;

class Config
{
    protected string $key;
    protected string $secret;
    protected string $table;
    protected string $region;
    protected string $version;

    /**
     * @param string $key
     * @param string $secret
     * @param string $table
     * @param string $region
     * @param string $version
     */
    public function __construct(
        string $key,
        string $secret,
        string $table,
        string $region = 'eu-central-1',
        $version = 'latest'
    ) {
        $this->key = $key;
        $this->secret = $secret;
        $this->table = $table;
        $this->region = $region;
        $this->version = $version;
    }

    /**
     * @return string
     */
    public function getKey(): string
    {
        return $this->key;
    }

    /**
     * @return string
     */
    public function getSecret(): string
    {
        return $this->secret;
    }

    /**
     * @return string
     */
    public function getTable(): string
    {
        return $this->table;
    }

    /**
     * @return string
     */
    public function getRegion(): string
    {
        return $this->region;
    }

    /**
     * @return string
     */
    public function getVersion(): string
    {
        return $this->version;
    }
}
