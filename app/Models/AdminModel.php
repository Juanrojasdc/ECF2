<?php

class Admin
{
    public function __construct(
        private int $adminId,
        private string $login,
        private string $passwordHash
    ) {
    }

    public function getAdminId(): int
    {
        return $this->adminId;
    }

    public function getLogin(): string
    {
        return $this->login;
    }

    public function getPasswordHash(): string
    {
        return $this->passwordHash;
    }
}