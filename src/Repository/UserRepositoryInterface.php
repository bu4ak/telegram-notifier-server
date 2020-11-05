<?php

namespace App\Repository;

use App\Entity\User;

interface UserRepositoryInterface
{
    public function findOneByToken(string $token): ?User;

    public function findOneByChatId(int $chatId): ?User;

    public function save(User $user): bool;
}
