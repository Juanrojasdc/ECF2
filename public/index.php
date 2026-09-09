<?php

require_once __DIR__ . '/../app/Core/Database.php';
require_once __DIR__ . '/../app/Core/Router.php';
require_once __DIR__ . '/../app/Models/TraineeModel.php';
require_once __DIR__ . '/../app/Repositories/TraineeRepository.php';
require_once __DIR__ . '/../app/Controllers/TraineeController.php';

$config = require __DIR__ . '/../config/database.php';

$database = new Database($config);
$pdo = $database->getConnection();

$traineeRepository = new TraineeRepository($pdo);
$traineeController = new TraineeController($traineeRepository);

$router = new Router();

$router->get('/trainees', function () use ($traineeController) {
    $traineeController->index();
});

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

$router->dispatch(
    $_SERVER['REQUEST_METHOD'],
    $path
);