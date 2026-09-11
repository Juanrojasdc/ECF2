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

        $isAdmin =
            AuthController::isAuthenticated();

        if (!$isAdmin) {
            unset(
                $statistics['sans_motif_by_trainee']
            );
        }

        require __DIR__ . '/../Views/statistics/index.php';
    }

    public function offcanvas(): void
    {
        $statistics =
            $this->statisticsService->getStatistics();

        $trainees =
            $this->traineeRepository->findAll();

        $isAdmin =
            AuthController::isAuthenticated();

        if (!$isAdmin) {
            unset(
                $statistics['sans_motif_by_trainee']
            );
        }

        require __DIR__ . '/../Views/statistics/offcanvas.php';
    }
}