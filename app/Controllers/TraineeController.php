<?php

class TraineeController
{
    private TraineeRepository $traineeRepository;

    public function __construct(TraineeRepository $traineeRepository)
    {
        $this->traineeRepository = $traineeRepository;
    }

    public function index(): void
    {
        $trainees = $this->traineeRepository->findAll();

        require __DIR__ . '/../Views/trainees/index.php';
    }


    // Creation

    public function showCreate(): void
    {
        $error = null;

        require __DIR__ . '/../Views/trainees/create.php';
    }

    public function create(): void
    {
        if (!Csrf::validate($_POST['csrf_token'] ?? null)) {
            http_response_code(403);
            echo '403 - Requête non autorisée';
            return;
        }

        // Validate submitted trainee details
        $data = $this->getTraineeData();

        if ($data === null) {
            $error = 'Les données envoyées sont invalides.';

            require __DIR__ . '/../Views/trainees/create.php';
            return;
        }

        $error = $this->validateTraineeData($data);

        if ($error !== null) {
            require __DIR__ . '/../Views/trainees/create.php';
            return;
        }

        // Optional photo upload
        $data['photo_path'] = null;

        try {
            $data['photo_path'] = PhotoUploadService::upload(
                $_FILES['photo'] ?? null
            );
        } catch (RuntimeException $exception) {
            $error = $exception->getMessage();

            require __DIR__ . '/../Views/trainees/create.php';
            return;
        }

        try {
            $this->traineeRepository->create($data);
        } catch (PDOException $exception) {
            if ($exception->getCode() === '23000') {
                $error = 'Cet identifiant AFPA existe déjà.';

                require __DIR__ . '/../Views/trainees/create.php';
                return;
            }

            throw $exception;
        }

        $_SESSION['flash_message'] =
            'Le stagiaire a été ajouté avec succès.';

        header('Location: ../trainees');
        exit;
    }


    // Editing

    public function showEdit(): void
    {
        $traineeId = $this->getPositiveInt($_GET['id'] ?? null);

        if ($traineeId === null) {
            http_response_code(400);
            echo '400 - Stagiaire invalide';
            return;
        }

        $trainee = $this->traineeRepository->findById($traineeId);

        if ($trainee === null) {
            http_response_code(404);
            echo '404 - Stagiaire introuvable';
            return;
        }

        $error = null;

        require __DIR__ . '/../Views/trainees/edit.php';
    }

    public function update(): void
    {
        if (!Csrf::validate($_POST['csrf_token'] ?? null)) {
            http_response_code(403);
            echo '403 - Requête non autorisée';
            return;
        }

        $traineeId = $this->getPositiveInt(
            $_POST['trainee_id'] ?? null
        );

        if ($traineeId === null) {
            http_response_code(400);
            echo '400 - Stagiaire invalide';
            return;
        }

        // Load the current trainee before applying changes.
        $trainee = $this->traineeRepository->findById($traineeId);

        if ($trainee === null) {
            http_response_code(404);
            echo '404 - Stagiaire introuvable';
            return;
        }

        $data = $this->getTraineeData();

        if ($data === null) {
            $error = 'Les données envoyées sont invalides.';

            require __DIR__ . '/../Views/trainees/edit.php';
            return;
        }

        $error = $this->validateTraineeData($data);

        if ($error !== null) {
            require __DIR__ . '/../Views/trainees/edit.php';
            return;
        }

        // Keep the current photo unless a replacement is uploaded.
        $data['photo_path'] = $trainee->getPhotoPath();

        try {
            $newPhotoPath = PhotoUploadService::upload(
                $_FILES['photo'] ?? null
            );

            if ($newPhotoPath !== null) {
                $data['photo_path'] = $newPhotoPath;
            }
        } catch (RuntimeException $exception) {
            $error = $exception->getMessage();

            require __DIR__ . '/../Views/trainees/edit.php';
            return;
        }

        try {
            $this->traineeRepository->update(
                $traineeId,
                $data
            );
        } catch (PDOException $exception) {
            if ($exception->getCode() === '23000') {
                $error = 'Cet identifiant AFPA existe déjà.';

                require __DIR__ . '/../Views/trainees/edit.php';
                return;
            }

            throw $exception;
        }

        $_SESSION['flash_message'] =
            'Le stagiaire a été modifié avec succès.';

        header('Location: ../trainees');
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

        $traineeId = $this->getPositiveInt(
            $_POST['trainee_id'] ?? null
        );

        if ($traineeId === null) {
            http_response_code(400);
            echo '400 - Stagiaire invalide';
            return;
        }

        $trainee = $this->traineeRepository->findById($traineeId);

        if ($trainee === null) {
            http_response_code(404);
            echo '404 - Stagiaire introuvable';
            return;
        }

        // Linked absences prevent deletion through the foreign key
        try {
            $this->traineeRepository->delete($traineeId);
        } catch (PDOException $exception) {
            if ($exception->getCode() === '23000') {
                $_SESSION['flash_message'] =
                    'Impossible de supprimer ce stagiaire car des absences lui sont associées.';

                header('Location: ../trainees');
                exit;
            }

            throw $exception;
        }

        $_SESSION['flash_message'] =
            'Le stagiaire a été supprimé avec succès.';

        header('Location: ../trainees');
        exit;
    }


    // Input validation

    private function getTraineeData(): ?array
    {
        $fields = [
            'afpa_id',
            'first_name',
            'last_name',
            'personal_email',
            'phone',
            'professional_url',
            'professional_email',
            'residence',
            'birth_date'
        ];

        $values = [];

        foreach ($fields as $field) {
            $value = $_POST[$field] ?? '';

            if (!is_string($value)) {
                return null;
            }

            $values[$field] = trim($value);
        }

        return [
            'afpa_id' => $values['afpa_id'],
            'first_name' => $values['first_name'],
            'last_name' => $values['last_name'],
            'personal_email' =>
                $values['personal_email'] !== ''
                    ? $values['personal_email']
                    : null,
            'phone' =>
                $values['phone'] !== ''
                    ? $values['phone']
                    : null,
            'professional_url' =>
                $values['professional_url'] !== ''
                    ? $values['professional_url']
                    : null,
            'professional_email' =>
                $values['professional_email'] !== ''
                    ? $values['professional_email']
                    : null,
            'residence' =>
                $values['residence'] !== ''
                    ? $values['residence']
                    : null,
            'birth_date' =>
                $values['birth_date'] !== ''
                    ? $values['birth_date']
                    : null
        ];
    }

    private function validateTraineeData(array $data): ?string
    {
        if (
            $data['afpa_id'] === '' ||
            $data['first_name'] === '' ||
            $data['last_name'] === ''
        ) {
            return 'Veuillez remplir les champs obligatoires.';
        }

        if (strlen($data['afpa_id']) > 20) {
            return 'L’identifiant AFPA ne doit pas dépasser 20 caractères.';
        }

        if (
            strlen($data['first_name']) > 100 ||
            strlen($data['last_name']) > 100
        ) {
            return 'Le prénom et le nom sont trop longs.';
        }

        if (
            $data['personal_email'] !== null &&
            !filter_var(
                $data['personal_email'],
                FILTER_VALIDATE_EMAIL
            )
        ) {
            return 'L’adresse e-mail personnelle est invalide.';
        }

        if (
            $data['professional_email'] !== null &&
            !filter_var(
                $data['professional_email'],
                FILTER_VALIDATE_EMAIL
            )
        ) {
            return 'L’adresse e-mail professionnelle est invalide.';
        }

        if (
            $data['personal_email'] !== null &&
            strlen($data['personal_email']) > 255
        ) {
            return 'L’adresse e-mail personnelle est trop longue.';
        }

        if (
            $data['professional_email'] !== null &&
            strlen($data['professional_email']) > 255
        ) {
            return 'L’adresse e-mail professionnelle est trop longue.';
        }

        if (
            $data['phone'] !== null &&
            strlen($data['phone']) > 30
        ) {
            return 'Le numéro de téléphone est trop long.';
        }

        if ($data['professional_url'] !== null) {
            if (
                strlen($data['professional_url']) > 255 ||
                !filter_var(
                    $data['professional_url'],
                    FILTER_VALIDATE_URL
                )
            ) {
                return 'L’URL professionnelle est invalide.';
            }

            $scheme = parse_url(
                $data['professional_url'],
                PHP_URL_SCHEME
            );

            if (!in_array($scheme, ['http', 'https'], true)) {
                return 'L’URL professionnelle doit utiliser HTTP ou HTTPS.';
            }
        }

        if (
            $data['residence'] !== null &&
            strlen($data['residence']) > 255
        ) {
            return 'Le lieu de résidence est trop long.';
        }

        if ($data['birth_date'] !== null) {
            $date = DateTime::createFromFormat(
                'Y-m-d',
                $data['birth_date']
            );

            if (
                $date === false ||
                $date->format('Y-m-d') !== $data['birth_date']
            ) {
                return 'La date de naissance est invalide.';
            }
        }

        return null;
    }

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
}