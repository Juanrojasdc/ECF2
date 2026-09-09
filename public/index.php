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

$config = require __DIR__ . '/../config/database.php';

require_once __DIR__
    . '/../app/Services/PhotoUploadService.php';


$database = new Database($config);
$pdo = $database->getConnection();

$traineeRepository = new TraineeRepository($pdo);
$traineeController = new TraineeController($traineeRepository);

$adminRepository = new AdminRepository($pdo);
$authController = new AuthController($adminRepository);

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


$router->post('/trainees/delete', function () use ($traineeController) {
    if (!AuthController::isAuthenticated()) {
        header('Location: ../login');
        exit;
    }

    $traineeController->delete();
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


/*
|--------------------------------------------------------------------------
| Dispatch
|--------------------------------------------------------------------------
*/

$router->dispatch(
    $_SERVER['REQUEST_METHOD'],
    $path
);