<?php

require_once __DIR__ . '/../Models/AbsenceModel.php';

class AbsenceRepository
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
                absence_id,
                absence_date,
                reason,
                justification_path,
                trainee_id
            FROM absences
            ORDER BY absence_date DESC
        ';

        $statement = $this->pdo->query($sql);
        $rows = $statement->fetchAll();

        $absences = [];

        foreach ($rows as $row) {
            $absences[] = new Absence(
                (int) $row['absence_id'],
                $row['absence_date'],
                $row['reason'],
                $row['justification_path'],
                (int) $row['trainee_id']
            );
        }

        return $absences;
    }

    public function create(array $data): bool
    {
        $sql = '
            INSERT INTO absences (
                absence_date,
                reason,
                justification_path,
                trainee_id
            ) VALUES (
                :absence_date,
                :reason,
                :justification_path,
                :trainee_id
            )
        ';

        $statement = $this->pdo->prepare($sql);

        return $statement->execute([
            'absence_date' => $data['absence_date'],
            'reason' => $data['reason'],
            'justification_path' => $data['justification_path'] ?? null,
            'trainee_id' => $data['trainee_id']
        ]);
    }

    public function delete(int $absenceId): bool
    {
        $sql = '
            DELETE FROM absences
            WHERE absence_id = :absence_id
        ';

        $statement = $this->pdo->prepare($sql);

        return $statement->execute([
            'absence_id' => $absenceId
        ]);
    }

    public function findById(int $absenceId): ?Absence
    {
        $sql = '
            SELECT
                absence_id,
                absence_date,
                reason,
                justification_path,
                trainee_id
            FROM absences
            WHERE absence_id = :absence_id
            LIMIT 1
        ';

        $statement = $this->pdo->prepare($sql);

        $statement->execute([
            'absence_id' => $absenceId
        ]);

        $row = $statement->fetch(PDO::FETCH_ASSOC);

        if ($row === false) {
            return null;
        }

        return new Absence(
            (int) $row['absence_id'],
            $row['absence_date'],
            $row['reason'],
            $row['justification_path'],
            (int) $row['trainee_id']
        );
    }

    public function update(int $absenceId, array $data): bool
    {
        $sql = '
            UPDATE absences
            SET
                absence_date = :absence_date,
                reason = :reason,
                justification_path = :justification_path,
                trainee_id = :trainee_id
            WHERE absence_id = :absence_id
        ';

        $statement = $this->pdo->prepare($sql);

        return $statement->execute([
            'absence_id' => $absenceId,
            'absence_date' => $data['absence_date'],
            'reason' => $data['reason'],
            'justification_path' => $data['justification_path'] ?? null,
            'trainee_id' => $data['trainee_id']
        ]);
    }

    public function countAll(): int
{
    $sql = '
        SELECT COUNT(*) AS total
        FROM absences
    ';

    $statement = $this->pdo->query($sql);
    $row = $statement->fetch(PDO::FETCH_ASSOC);

    return (int) $row['total'];
}


public function countByReason(): array
{
    $sql = '
        SELECT
            reason,
            COUNT(*) AS total
        FROM absences
        GROUP BY reason
    ';

    $statement = $this->pdo->query($sql);
    $rows = $statement->fetchAll(PDO::FETCH_ASSOC);

    $counts = [];

    foreach ($rows as $row) {
        $counts[$row['reason']] = (int) $row['total'];
    }

    return $counts;
}

}