<?php
namespace Infrastructure\Adapters;

use Infrastructure\Persistence\UserRepository;
use Domain\Entities\User;
use PDO;

class UserRepositoryMySQL implements UserRepository
{
    public function __construct(private PDO $pdo) {}

    public function save(User $user): void
    {
        $stmt = $this->pdo->prepare("INSERT INTO users (name, email) VALUES (?, ?)");
        $stmt->execute([$user->getName(), $user->getEmail()]);
    }

    public function findAll(): array
    {
        $stmt = $this->pdo->query("SELECT id, name, email FROM users ORDER BY id DESC");
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Si prefieres devolver entidades:
        $users = [];
        foreach ($rows as $r) {
            $users[] = new User($r['name'], $r['email'], (int)$r['id']);
        }
        return $users;
    }
}
