<?php
namespace Domain\Entities;

class User {
    private int $id;
    private string $name;
    private string $email;

    public function __construct(string $name, string $email, int $id = 0) {
        $this->id = $id;
        $this->name = $name;
        $this->email = $email;
    }

    public function getId(): int {
        return $this->id;
    }

    public function getName(): string {
        return $this->name;
    }

    public function getEmail(): string {
        return $this->email;
    }
}
