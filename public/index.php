<?php

require_once __DIR__ . '/../app/Core/Database.php';
require_once __DIR__ . '/../app/Models/TraineeModel.php';
require_once __DIR__ . '/../app/Repositories/TraineeRepository.php';
require_once __DIR__ . '/../app/Controllers/TraineeController.php';

$config = require __DIR__ . '/../config/database.php';

$database = new Database($config);
$pdo = $database->getConnection();

$traineeRepository = new TraineeRepository($pdo);
$traineeController = new TraineeController($traineeRepository);

$traineeController->index();