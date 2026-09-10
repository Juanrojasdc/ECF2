<?php

session_start();

require_once __DIR__ . '/../app/Core/Database.php';
require_once __DIR__ . '/../app/Core/Router.php';
require_once __DIR__ . '/../app/Core/Csrf.php';

require_once __DIR__ . '/../app/Models/TraineeModel.php';
require_once __DIR__ . '/../app/Repositories/TraineeRepository.php';
require_once __DIR__ . '/../app/Controllers/TraineeController.php';

require_once __DIR__ . '/../app/Models/AdminModel.php';
require_once __DIR__ . '/../app/Repositories/AdminRepository.php';
require_once __DIR__ . '/../app/Controllers/AuthController.php';

require_once __DIR__ . '/../app/Models/AbsenceModel.php';
require_once __DIR__ . '/../app/Repositories/AbsenceRepository.php';
require_once __DIR__ . '/../app/Controllers/AbsenceController.php';

$config = require __DIR__ . '/../config/database.php';

require_once __DIR__
    . '/../app/Services/PhotoUploadService.php';


$database = new Database($config);
$pdo = $database->getConnection();

$traineeRepository = new TraineeRepository($pdo);
$traineeController = new TraineeController($traineeRepository);

$adminRepository = new AdminRepository($pdo);
$authController = new AuthController($adminRepository);

$absenceRepository = new AbsenceRepository($pdo);
$absenceController = new AbsenceController(
    $absenceRepository,
    $traineeRepository
);

$router = new Router();


/*
|--------------------------------------------------------------------------
| Trainees
|--------------------------------------------------------------------------
*/

$router->get('/trainees', function () use ($traineeController) {
    if (!AuthController::isAuthenticated()) {
        $_SESSION['flash_message'] =
            'Vous devez vous connecter pour accéder à cette page.';

        header('Location: login');
        exit;
    }

    $traineeController->index();
});


$router->get('/trainees/create', function () use ($traineeController) {
    if (!AuthController::isAuthenticated()) {
        header('Location: ../login');
        exit;
    }

    $traineeController->showCreate();
});


$router->post('/trainees/create', function () use ($traineeController) {
    if (!AuthController::isAuthenticated()) {
        header('Location: ../login');
        exit;
    }

    $traineeController->create();
});


$router->get('/trainees/edit', function () use ($traineeController) {
    if (!AuthController::isAuthenticated()) {
        header('Location: ../login');
        exit;
    }

    $traineeController->showEdit();
});


$router->post('/trainees/edit', function () use ($traineeController) {
    if (!AuthController::isAuthenticated()) {
        header('Location: ../login');
        exit;
    }

    $traineeController->update();
});


$router->post('/trainees/delete', function () use ($traineeController) {
    if (!AuthController::isAuthenticated()) {
        header('Location: ../login');
        exit;
    }

    $traineeController->delete();
});


/*
|--------------------------------------------------------------------------
| Absences
|--------------------------------------------------------------------------
*/

$router->get('/absences', function () use ($absenceController) {
    if (!AuthController::isAuthenticated()) {
        $_SESSION['flash_message'] =
            'Vous devez vous connecter pour accéder à cette page.';

        header('Location: login');
        exit;
    }

    $absenceController->index();
});


$router->get('/absences/create', function () use ($absenceController) {
    if (!AuthController::isAuthenticated()) {
        header('Location: ../login');
        exit;
    }

    $absenceController->showCreate();
});


$router->post('/absences/create', function () use ($absenceController) {
    if (!AuthController::isAuthenticated()) {
        header('Location: ../login');
        exit;
    }

    $absenceController->create();
});


$router->get('/absences/edit', function () use ($absenceController) {
    if (!AuthController::isAuthenticated()) {
        header('Location: ../login');
        exit;
    }

    $absenceController->showEdit();
});


$router->post('/absences/edit', function () use ($absenceController) {
    if (!AuthController::isAuthenticated()) {
        header('Location: ../login');
        exit;
    }

    $absenceController->update();
});


$router->post('/absences/delete', function () use ($absenceController) {
    if (!AuthController::isAuthenticated()) {
        header('Location: ../login');
        exit;
    }

    $absenceController->delete();
});


/*
|--------------------------------------------------------------------------
| Authentication
|--------------------------------------------------------------------------
*/

$router->get('/login', function () use ($authController) {
    $authController->showLogin();
});


$router->post('/login', function () use ($authController) {
    $authController->login();
});


$router->post('/logout', function () use ($authController) {
    $authController->logout();
});


/*
|--------------------------------------------------------------------------
| Request path
|--------------------------------------------------------------------------
*/

$requestPath = parse_url(
    $_SERVER['REQUEST_URI'],
    PHP_URL_PATH
);

$basePath = rtrim(
    dirname($_SERVER['SCRIPT_NAME']),
    '/'
);

$path = substr(
    $requestPath,
    strlen($basePath)
);

if ($path === '') {
    $path = '/';
}


/*
|--------------------------------------------------------------------------
| Dispatch
|--------------------------------------------------------------------------
*/

$router->dispatch(
    $_SERVER['REQUEST_METHOD'],
    $path
);