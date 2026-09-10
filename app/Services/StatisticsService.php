<?php

class StatisticsService
{
    private AbsenceRepository $absenceRepository;

    public function __construct(
        AbsenceRepository $absenceRepository
    ) {
        $this->absenceRepository = $absenceRepository;
    }

   public function getStatistics(): array
{
    $totalAbsences = $this->absenceRepository->countAll();

    $countsByReason =
        $this->absenceRepository->countByReason();

    $sansMotifByTrainee =
        $this->absenceRepository->countSansMotifByTrainee();

    $reasons = [
        'maladie',
        'sans motif',
        'absence légale',
        'accident du travail'
    ];

    $reasonStatistics = [];

    foreach ($reasons as $reason) {
        $reasonStatistics[$reason] =
            $countsByReason[$reason] ?? 0;
    }

    return [
        'total_absences' => $totalAbsences,
        'by_reason' => $reasonStatistics,
        'sans_motif_by_trainee' => $sansMotifByTrainee
    ];
}
}