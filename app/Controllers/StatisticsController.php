<?php

class StatisticsController
{
    private StatisticsService $statisticsService;
    private TraineeRepository $traineeRepository;

    public function __construct(
        StatisticsService $statisticsService,
        TraineeRepository $traineeRepository
    ) {
        $this->statisticsService = $statisticsService;
        $this->traineeRepository = $traineeRepository;
    }

    public function index(): void
    {
        $statistics =
            $this->statisticsService->getStatistics();

        $trainees =
            $this->traineeRepository->findAll();

        require __DIR__ . '/../Views/statistics/index.php';
    }
}