<?php

session_start();
// ini set para configurar la visualización de errores y el registro de errores, set_exception_handler para manejar excepciones no capturadas y registrar los detalles del error en el registro de errores, y luego mostrar un mensaje genérico al usuario. Esto ayuda a mantener la seguridad y la estabilidad de la aplicación al evitar que los detalles del error se muestren directamente al usuario final.
error_reporting(E_ALL);
ini_set('display_errors', '0');
ini_set('display_startup_errors', '0');
ini_set('log_errors', '1');

set_exception_handler(function (Throwable $exception): void {

    error_log($exception->__toString());

    if (!headers_sent()) {
        http_response_code(500);
        header('Content-Type: text/html; charset=UTF-8');
    }

    echo 'Une erreur interne est survenue. Veuillez réessayer.';
});

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

require_once __DIR__ . '/../app/Services/PdfUploadService.php';

require_once __DIR__
    . '/../app/Services/PhotoUploadService.php';

require_once __DIR__ . '/../app/Services/StatisticsService.php';
require_once __DIR__ . '/../app/Controllers/StatisticsController.php';


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

$statisticsService = new StatisticsService(
    $absenceRepository
);

$statisticsController = new StatisticsController(
    $statisticsService,
    $traineeRepository
);

$router = new Router();



/*
|--------------------------------------------------------------------------
| Statistics
|--------------------------------------------------------------------------
*/

$router->get('/statistics', function () use ($statisticsController) {
    $statisticsController->index();
});

$router->get('/statistics/offcanvas', function () use ($statisticsController) {
    $statisticsController->offcanvas();
});



/*
|--------------------------------------------------------------------------
| Home
|--------------------------------------------------------------------------
*/

$router->get('/', function () {
    if (AuthController::isAuthenticated()) {
        header('Location: trainees');
        exit;
    }

    header('Location: login');
    exit;
});


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

$router->get(
    '/absences/justification',
    function () use ($absenceController) {
        if (!AuthController::isAuthenticated()) {
            header('Location: ../login');
            exit;
        }

        $absenceController->showJustification();
    }
);


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