<?php

require_once __DIR__ . '/../Models/TraineeModel.php';
class TraineeRepository
{

//me conecto a la base de datos usando database.php y PDO, lo que me permite ejecutar consultas SQL de manera segura y eficiente. PDO proporciona una capa de abstracción para interactuar con diferentes bases de datos, lo que facilita la portabilidad del código y mejora la seguridad al prevenir inyecciones SQL mediante el uso de declaraciones preparadas.
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

public function create(array $data): bool
{
    $sql = '
        INSERT INTO trainees (
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
        ) VALUES (
            :afpa_id,
            :first_name,
            :last_name,
            :personal_email,
            :phone,
            :professional_url,
            :professional_email,
            :residence,
            :birth_date,
            :photo_path
        )
    ';

    $statement = $this->pdo->prepare($sql);

    return $statement->execute([
        'afpa_id' => $data['afpa_id'],
        'first_name' => $data['first_name'],
        'last_name' => $data['last_name'],
        'personal_email' => $data['personal_email'],
        'phone' => $data['phone'],
        'professional_url' => $data['professional_url'],
        'professional_email' => $data['professional_email'],
        'residence' => $data['residence'],
        'birth_date' => $data['birth_date'],
        'photo_path' => $data['photo_path']
    ]);
}

public function delete(int $traineeId): bool
{
    $sql = '
        DELETE FROM trainees
        WHERE trainee_id = :trainee_id
    ';

    $statement = $this->pdo->prepare($sql);

    return $statement->execute([
        'trainee_id' => $traineeId
    ]);
}

public function findById(int $traineeId): ?Trainee
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
        WHERE trainee_id = :trainee_id
        LIMIT 1
    ';

    $statement = $this->pdo->prepare($sql);

    $statement->execute([
        'trainee_id' => $traineeId
    ]);

    $row = $statement->fetch(PDO::FETCH_ASSOC);

    if ($row === false) {
        return null;
    }

    return new Trainee(
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
public function update(int $traineeId, array $data): bool
{
    $sql = '
        UPDATE trainees
        SET
            afpa_id = :afpa_id,
            first_name = :first_name,
            last_name = :last_name,
            personal_email = :personal_email,
            phone = :phone,
            professional_url = :professional_url,
            professional_email = :professional_email,
            residence = :residence,
            birth_date = :birth_date,
            photo_path = :photo_path
        WHERE trainee_id = :trainee_id
    ';

    $statement = $this->pdo->prepare($sql);

    return $statement->execute([
        'trainee_id' => $traineeId,
        'afpa_id' => $data['afpa_id'],
        'first_name' => $data['first_name'],
        'last_name' => $data['last_name'],
        'personal_email' => $data['personal_email'],
        'phone' => $data['phone'],
        'professional_url' => $data['professional_url'],
        'professional_email' => $data['professional_email'],
        'residence' => $data['residence'],
        'birth_date' => $data['birth_date'],
        'photo_path' => $data['photo_path']
    ]);
}

}