<?php
namespace Infrastructure\Persistence;

use Domain\Entities\User;

interface UserRepository
{
    public function save(User $user): void;
    public function findAll(): array;
}
