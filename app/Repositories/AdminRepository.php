<?php

require_once __DIR__ . '/../Models/AdminModel.php';

class AdminRepository
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function findByLogin(string $login): ?Admin
    {
        $sql = '
            SELECT
                admin_id,
                login,
                password_hash
            FROM admins
            WHERE login = :login
            LIMIT 1
        ';

        $statement = $this->pdo->prepare($sql);

        $statement->execute([
            'login' => $login
        ]);

        $row = $statement->fetch(PDO::FETCH_ASSOC);

        if ($row === false) {
            return null;
        }

        return new Admin(
            (int) $row['admin_id'],
            $row['login'],
            $row['password_hash']
        );
    }
}