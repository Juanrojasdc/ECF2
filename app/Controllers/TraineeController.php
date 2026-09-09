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


    /*
    |--------------------------------------------------------------------------
    | CREATE
    |--------------------------------------------------------------------------
    */

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

        $data = [
            'afpa_id' => trim($_POST['afpa_id'] ?? ''),
            'first_name' => trim($_POST['first_name'] ?? ''),
            'last_name' => trim($_POST['last_name'] ?? ''),
            'personal_email' => trim($_POST['personal_email'] ?? '') ?: null,
            'phone' => trim($_POST['phone'] ?? '') ?: null,
            'professional_url' => trim($_POST['professional_url'] ?? '') ?: null,
            'professional_email' => trim($_POST['professional_email'] ?? '') ?: null,
            'residence' => trim($_POST['residence'] ?? '') ?: null,
            'birth_date' => ($_POST['birth_date'] ?? '') ?: null,
            'photo_path' => null
        ];

        if (
            $data['afpa_id'] === '' ||
            $data['first_name'] === '' ||
            $data['last_name'] === ''
        ) {
            $error = 'Veuillez remplir les champs obligatoires.';

            require __DIR__ . '/../Views/trainees/create.php';
            return;
        }

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


    /*
    |--------------------------------------------------------------------------
    | UPDATE
    |--------------------------------------------------------------------------
    */

    public function showEdit(): void
    {
        $traineeId = (int) ($_GET['id'] ?? 0);

        if ($traineeId <= 0) {
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

        $traineeId = (int) ($_POST['trainee_id'] ?? 0);

        if ($traineeId <= 0) {
            http_response_code(400);
            echo '400 - Stagiaire invalide';
            return;
        }

        /*
         * Recuperamos el trainee actual porque necesitamos
         * conservar su foto si el administrador no sube una nueva.
         */
        $trainee = $this->traineeRepository->findById($traineeId);

        if ($trainee === null) {
            http_response_code(404);
            echo '404 - Stagiaire introuvable';
            return;
        }

        $data = [
            'afpa_id' => trim($_POST['afpa_id'] ?? ''),
            'first_name' => trim($_POST['first_name'] ?? ''),
            'last_name' => trim($_POST['last_name'] ?? ''),
            'personal_email' => trim($_POST['personal_email'] ?? '') ?: null,
            'phone' => trim($_POST['phone'] ?? '') ?: null,
            'professional_url' => trim($_POST['professional_url'] ?? '') ?: null,
            'professional_email' => trim($_POST['professional_email'] ?? '') ?: null,
            'residence' => trim($_POST['residence'] ?? '') ?: null,
            'birth_date' => ($_POST['birth_date'] ?? '') ?: null,

            // Por defecto conservamos la foto actual.
            'photo_path' => $trainee->getPhotoPath()
        ];

        if (
            $data['afpa_id'] === '' ||
            $data['first_name'] === '' ||
            $data['last_name'] === ''
        ) {
            $error = 'Veuillez remplir les champs obligatoires.';

            require __DIR__ . '/../Views/trainees/edit.php';
            return;
        }

        /*
         * Si se ha seleccionado una nueva foto:
         * se valida, se guarda físicamente y obtenemos su nueva ruta.
         *
         * Si no se seleccionó ninguna:
         * PhotoUploadService devuelve null y conservamos la anterior.
         */

        
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

        $traineeId = (int) ($_POST['trainee_id'] ?? 0);

        if ($traineeId <= 0) {
            http_response_code(400);
            echo '400 - Stagiaire invalide';
            return;
        }

        $this->traineeRepository->delete($traineeId);

        $_SESSION['flash_message'] =
            'Le stagiaire a été supprimé avec succès.';

        header('Location: ../trainees');
        exit;
    }
}