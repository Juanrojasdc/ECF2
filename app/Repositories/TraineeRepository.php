<?php

require_once __DIR__ . '/../Models/TraineeModel.php';
class TraineeRepository
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

//Pensé  en usar select * from trainees, pero es mejor practicar el SELECT con los campos que quiero mostrar, además de que es más seguro y eficiente.

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
    $rows = $statement->fetchAll();

    $trainees = [];

    foreach ($rows as $row) {
        $trainees[] = new Trainee(
            (int) $row['trainee_id'],
            $row['afpa_id'],
            $row['first_name'],
            $row['last_name'],
            $row['personal_email'],
            $row['phone'],
            $row['professional_url'],
            $row['professional_email'],
            $row['residence'],
            $row['birth_date'],
            $row['photo_path']
        );
    }

    return $trainees;
}
}