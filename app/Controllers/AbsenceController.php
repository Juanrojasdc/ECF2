<?php

class AbsenceController
{
    private AbsenceRepository $absenceRepository;
    private TraineeRepository $traineeRepository;

    public function __construct(
        AbsenceRepository $absenceRepository,
        TraineeRepository $traineeRepository
    ) {
        $this->absenceRepository = $absenceRepository;
        $this->traineeRepository = $traineeRepository;
    }

    public function index(): void
    {
        $absences = $this->absenceRepository->findAll();

        $trainees = $this->traineeRepository->findAll();

        require __DIR__ . '/../Views/absences/index.php';
    }

    /*

-----------Create------------------


*/


public function showCreate(): void
{
    $trainees = $this->traineeRepository->findAll();
    $error = null;

    require __DIR__ . '/../Views/absences/create.php';
}

 public function create(): void
{
    if (!Csrf::validate($_POST['csrf_token'] ?? null)) {
        http_response_code(403);
        echo '403 - Requête non autorisée';
        return;
    }

    $data = [
        'absence_date' => trim($_POST['absence_date'] ?? ''),
        'reason' => trim($_POST['reason'] ?? ''),
        'justification_path' => null,
        'trainee_id' => (int) ($_POST['trainee_id'] ?? 0)
    ];

    if (
        $data['absence_date'] === '' ||
        $data['reason'] === '' ||
        $data['trainee_id'] <= 0
    ) {
        $error = 'Veuillez remplir les champs obligatoires.';

        $trainees = $this->traineeRepository->findAll();

        require __DIR__ . '/../Views/absences/create.php';
        return;
    }

    $allowedReasons = [
        'maladie',
        'sans motif',
        'absence légale',
        'accident du travail'
    ];

    if (!in_array($data['reason'], $allowedReasons, true)) {
        $error = 'Le motif sélectionné est invalide.';

        $trainees = $this->traineeRepository->findAll();

        require __DIR__ . '/../Views/absences/create.php';
        return;
    }

    $date = DateTime::createFromFormat(
        'Y-m-d',
        $data['absence_date']
    );

    if (
        !$date ||
        $date->format('Y-m-d') !== $data['absence_date']
    ) {
        $error = 'La date d\'absence est invalide.';

        $trainees = $this->traineeRepository->findAll();

        require __DIR__ . '/../Views/absences/create.php';
        return;
    }

    $trainee = $this->traineeRepository->findById(
        $data['trainee_id']
    );

    if ($trainee === null) {
        $error = 'Le stagiaire sélectionné est invalide.';

        $trainees = $this->traineeRepository->findAll();

        require __DIR__ . '/../Views/absences/create.php';
        return;
    }

    $this->absenceRepository->create($data);

    $_SESSION['flash_message'] =
        'L\'absence a été ajoutée avec succès.';

    header('Location: ../absences');
    exit;
}


/*
|--------------------------------------------------------------------------
| UPDATE
|--------------------------------------------------------------------------
*/

public function showEdit(): void
{
    $absenceId = (int) ($_GET['id'] ?? 0);

    if ($absenceId <= 0) {
        http_response_code(400);
        echo '400 - Absence invalide';
        return;
    }

    $absence = $this->absenceRepository->findById($absenceId);

    if ($absence === null) {
        http_response_code(404);
        echo '404 - Absence introuvable';
        return;
    }

    $trainees = $this->traineeRepository->findAll();

    $error = null;

    require __DIR__ . '/../Views/absences/edit.php';
}

public function update(): void
{
    if (!Csrf::validate($_POST['csrf_token'] ?? null)) {
        http_response_code(403);
        echo '403 - Requête non autorisée';
        return;
    }

    $absenceId = (int) ($_POST['absence_id'] ?? 0);

    if ($absenceId <= 0) {
        http_response_code(400);
        echo '400 - Absence invalide';
        return;
    }

    $absence = $this->absenceRepository->findById($absenceId);

    if ($absence === null) {
        http_response_code(404);
        echo '404 - Absence introuvable';
        return;
    }

    $data = [
        'absence_date' => trim($_POST['absence_date'] ?? ''),
        'reason' => trim($_POST['reason'] ?? ''),
        'justification_path' => $absence->getJustificationPath(),
        'trainee_id' => (int) ($_POST['trainee_id'] ?? 0)
    ];

    if (
        $data['absence_date'] === '' ||
        $data['reason'] === '' ||
        $data['trainee_id'] <= 0
    ) {
        $error = 'Veuillez remplir les champs obligatoires.';
        $trainees = $this->traineeRepository->findAll();

        require __DIR__ . '/../Views/absences/edit.php';
        return;
    }

    $allowedReasons = [
        'maladie',
        'sans motif',
        'absence légale',
        'accident du travail'
    ];

    if (!in_array($data['reason'], $allowedReasons, true)) {
        $error = 'Le motif sélectionné est invalide.';
        $trainees = $this->traineeRepository->findAll();

        require __DIR__ . '/../Views/absences/edit.php';
        return;
    }

    $date = DateTime::createFromFormat(
        'Y-m-d',
        $data['absence_date']
    );

    if (
        !$date ||
        $date->format('Y-m-d') !== $data['absence_date']
    ) {
        $error = 'La date d\'absence est invalide.';
        $trainees = $this->traineeRepository->findAll();

        require __DIR__ . '/../Views/absences/edit.php';
        return;
    }

    $trainee = $this->traineeRepository->findById(
        $data['trainee_id']
    );

    if ($trainee === null) {
        $error = 'Le stagiaire sélectionné est invalide.';
        $trainees = $this->traineeRepository->findAll();

        require __DIR__ . '/../Views/absences/edit.php';
        return;
    }

    $this->absenceRepository->update(
        $absenceId,
        $data
    );

    $_SESSION['flash_message'] =
        'L\'absence a été modifiée avec succès.';

    header('Location: ../absences');
    exit;
}

/*
|--------------------------------------------------------------------------
| DELETE
|--------------------------------------------------------------------------
*/

public function delete(): void
{
    if (!Csrf::validate($_POST['csrf_token'] ?? null)) {
        http_response_code(403);
        echo '403 - Requête non autorisée';
        return;
    }

    $absenceId = (int) ($_POST['absence_id'] ?? 0);

    if ($absenceId <= 0) {
        http_response_code(400);
        echo '400 - Absence invalide';
        return;
    }

    $absence = $this->absenceRepository->findById($absenceId);

    if ($absence === null) {
        http_response_code(404);
        echo '404 - Absence introuvable';
        return;
    }

    $this->absenceRepository->delete($absenceId);

    $_SESSION['flash_message'] =
        'L\'absence a été supprimée avec succès.';

    header('Location: ../absences');
    exit;
}
    
}