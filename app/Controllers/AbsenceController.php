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


    // Creation

    public function showCreate(): void
    {
        $trainees = $this->traineeRepository->findAll();
        $error = null;

        $selectedTraineeId = $this->getPositiveInt(
            $_GET['trainee_id'] ?? null
        ) ?? 0;

        require __DIR__ . '/../Views/absences/create.php';
    }

    public function create(): void
    {
        if (!Csrf::validate($_POST['csrf_token'] ?? null)) {
            http_response_code(403);
            echo '403 - Requête non autorisée';
            return;
        }

        $absenceDate = $this->getString(
            $_POST['absence_date'] ?? null
        );

        $reason = $this->getString(
            $_POST['reason'] ?? null
        );

        $traineeId = $this->getPositiveInt(
            $_POST['trainee_id'] ?? null
        );

        if (
            $absenceDate === null ||
            $reason === null ||
            $traineeId === null
        ) {
            $error = 'Les données envoyées sont invalides.';
            $trainees = $this->traineeRepository->findAll();

            require __DIR__ . '/../Views/absences/create.php';
            return;
        }

        $data = [
            'absence_date' => $absenceDate,
            'reason' => $reason,
            'justification_path' => null,
            'trainee_id' => $traineeId
        ];

        if (
            $data['absence_date'] === '' ||
            $data['reason'] === ''
        ) {
            $error = 'Veuillez remplir les champs obligatoires.';
            $trainees = $this->traineeRepository->findAll();

            require __DIR__ . '/../Views/absences/create.php';
            return;
        }

        // Allowed full-day absence reasons
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

        if (!$this->isValidDate($data['absence_date'])) {
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

        // Optional supporting PDF
        try {
            $data['justification_path'] =
                PdfUploadService::upload(
                    $_FILES['justification'] ?? null
                );
        } catch (RuntimeException $exception) {
            $error = $exception->getMessage();
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


    // Editing

    public function showEdit(): void
    {
        $absenceId = $this->getPositiveInt(
            $_GET['id'] ?? null
        );

        if ($absenceId === null) {
            http_response_code(400);
            echo '400 - Absence invalide';
            return;
        }

        $absence = $this->absenceRepository->findById(
            $absenceId
        );

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

        $absenceId = $this->getPositiveInt(
            $_POST['absence_id'] ?? null
        );

        if ($absenceId === null) {
            http_response_code(400);
            echo '400 - Absence invalide';
            return;
        }

        $absence = $this->absenceRepository->findById(
            $absenceId
        );

        if ($absence === null) {
            http_response_code(404);
            echo '404 - Absence introuvable';
            return;
        }

        $absenceDate = $this->getString(
            $_POST['absence_date'] ?? null
        );

        $reason = $this->getString(
            $_POST['reason'] ?? null
        );

        $traineeId = $this->getPositiveInt(
            $_POST['trainee_id'] ?? null
        );

        if (
            $absenceDate === null ||
            $reason === null ||
            $traineeId === null
        ) {
            $error = 'Les données envoyées sont invalides.';
            $trainees = $this->traineeRepository->findAll();

            require __DIR__ . '/../Views/absences/edit.php';
            return;
        }

        $data = [
            'absence_date' => $absenceDate,
            'reason' => $reason,
            'justification_path' =>
                $absence->getJustificationPath(),
            'trainee_id' => $traineeId
        ];

        if (
            $data['absence_date'] === '' ||
            $data['reason'] === ''
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

        if (!$this->isValidDate($data['absence_date'])) {
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

        // Keep the existing PDF when no replacement is uploaded
        try {
            $newJustificationPath =
                PdfUploadService::upload(
                    $_FILES['justification'] ?? null
                );

            if ($newJustificationPath !== null) {
                $data['justification_path'] =
                    $newJustificationPath;
            }
        } catch (RuntimeException $exception) {
            $error = $exception->getMessage();
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


    // Protected PDF delivery

    public function showJustification(): void
    {
        $absenceId = $this->getPositiveInt(
            $_GET['id'] ?? null
        );

        if ($absenceId === null) {
            http_response_code(400);
            echo '400 - Absence invalide';
            return;
        }

        $absence = $this->absenceRepository->findById(
            $absenceId
        );

        if ($absence === null) {
            http_response_code(404);
            echo '404 - Absence introuvable';
            return;
        }

        // Resolve the document from the absence, not a client-supplied path
        $justificationPath =
            $absence->getJustificationPath();

        if ($justificationPath === null) {
            http_response_code(404);
            echo '404 - Justificatif introuvable';
            return;
        }

        $filePath =
            __DIR__ . '/../../' . $justificationPath;

        if (!is_file($filePath)) {
            http_response_code(404);
            echo '404 - Fichier introuvable';
            return;
        }

        header('Content-Type: application/pdf');

        header(
            'Content-Disposition: inline; filename="justificatif.pdf"'
        );

        header(
            'Content-Length: ' . filesize($filePath)
        );

        readfile($filePath);
        exit;
    }


    // Deletion

    public function delete(): void
    {
        if (!Csrf::validate($_POST['csrf_token'] ?? null)) {
            http_response_code(403);
            echo '403 - Requête non autorisée';
            return;
        }

        $absenceId = $this->getPositiveInt(
            $_POST['absence_id'] ?? null
        );

        if ($absenceId === null) {
            http_response_code(400);
            echo '400 - Absence invalide';
            return;
        }

        $absence = $this->absenceRepository->findById(
            $absenceId
        );

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


    // Input validation

    private function getPositiveInt(mixed $value): ?int
    {
        if (!is_string($value) && !is_int($value)) {
            return null;
        }

        $validatedValue = filter_var(
            $value,
            FILTER_VALIDATE_INT,
            [
                'options' => [
                    'min_range' => 1
                ]
            ]
        );

        if ($validatedValue === false) {
            return null;
        }

        return $validatedValue;
    }

    private function getString(mixed $value): ?string
    {
        if (!is_string($value)) {
            return null;
        }

        return trim($value);
    }

    private function isValidDate(string $value): bool
    {
        $date = DateTime::createFromFormat(
            'Y-m-d',
            $value
        );

        if (
            $date === false ||
            $date->format('Y-m-d') !== $value
        ) {
            return false;
        }

        // Match the MySQL DATE year range
        $year = (int) $date->format('Y');

        return $year >= 1000 && $year <= 9999;
    }
}