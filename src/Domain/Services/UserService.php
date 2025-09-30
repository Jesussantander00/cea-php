<?php
namespace Domain\Services;

use Domain\Entities\User;
use Infrastructure\Adapters\UserRepositoryMySQL;

class UserService
{
    private UserRepositoryMySQL $repo;

    public function __construct(UserRepositoryMySQL $repo)
    {
        $this->repo = $repo;
    }

    // Tu index.php llama $service->register($user)
    public function register(User $user): void
    {
        $this->repo->save($user);
    }

    // Tu index.php llama $service->listUsers()
    public function listUsers(): array
    {
        return $this->repo->findAll();
    }
}
