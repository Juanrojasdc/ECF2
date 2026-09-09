<?php

require_once __DIR__ . '/../Repositories/AdminRepository.php';

class AuthController
{
    public function __construct(
        private AdminRepository $adminRepository
    ) {
    }

    public static function isAuthenticated(): bool
{
    return isset($_SESSION['admin_id']);
}

    public function showLogin(): void
    {
        $error = null;

        require __DIR__ . '/../Views/auth/login.php';
    }

    public function login(): void
    {
        $login = trim($_POST['login'] ?? '');
        $password = $_POST['password'] ?? '';

        if ($login === '' || $password === '') {
            $error = 'Veuillez remplir tous les champs.';
            require __DIR__ . '/../Views/auth/login.php';
            return;
        }

        $admin = $this->adminRepository->findByLogin($login);

        if (
            $admin === null ||
            !password_verify($password, $admin->getPasswordHash())
        ) {
            $error = 'Identifiant ou mot de passe incorrect.';
            require __DIR__ . '/../Views/auth/login.php';
            return;
        }

        session_regenerate_id(true);

        $_SESSION['admin_id'] = $admin->getAdminId();
        $_SESSION['admin_login'] = $admin->getLogin();

        header('Location: trainees');
        exit;
    }

  public function logout(): void
{
    if (!Csrf::validate($_POST['csrf_token'] ?? null)) {
        http_response_code(403);
        echo '403 - Requête non autorisée';
        return;
    }

    $_SESSION = [];

    session_regenerate_id(true);

    $_SESSION['flash_message'] =
        'Vous avez été déconnecté avec succès.';

    header('Location: login');
    exit;
}
}
