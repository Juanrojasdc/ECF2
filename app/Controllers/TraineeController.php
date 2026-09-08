<?php

class TraineeController
{
    private TraineeRepository $traineeRepository; 

    public function __construct(TraineeRepository $traineeRepository) // Inyección de dependencia del repositorio de trainees, con esto el controlador puede acceder a los métodos del repositorio para obtener los datos de los trainees desde la base de datos.
    {
        $this->traineeRepository = $traineeRepository;
    }

    public function index(): void // Método que maneja la acción de mostrar la lista de trainees. Este método obtiene todos los trainees del repositorio y luego carga la vista correspondiente para mostrarlos.
    {
        $trainees = $this->traineeRepository->findAll();

        require __DIR__ . '/../Views/trainees/index.php';
    }
}