<?php

class TraineeRepository
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function findAll(): array
    {
        $sql = '
            SELECT
                trainee_id,
                afpa_id,
                first_name,
                last_name,
                personal_email,
                phone,
                professional_url,
                professional_email,
                residence,
                birth_date,
                photo_path
            FROM trainees
            ORDER BY last_name, first_name
        ';

        $statement = $this->pdo->query($sql);

        return $statement->fetchAll();
    }
}