<?php

class StatisticsService
{
    private const MONTHLY_INCOME = 712;
    private const WORKING_DAYS = 21;

    private AbsenceRepository $absenceRepository;

    public function __construct(
        AbsenceRepository $absenceRepository
    ) {
        $this->absenceRepository = $absenceRepository;
    }

    public function getStatistics(): array
    {
        $totalAbsences =
            $this->absenceRepository->countAll();

        $countsByReason =
            $this->absenceRepository->countByReason();

        $sansMotifByTrainee =
            $this->absenceRepository->countSansMotifByTrainee();

        $absencesByTrainee =
            $this->absenceRepository->countByTrainee();

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

        $dailyIncome =
            self::MONTHLY_INCOME / self::WORKING_DAYS;

        $estimatedTotalLoss =
            $totalAbsences * $dailyIncome;

        return [
            'total_absences' => $totalAbsences,
            'by_reason' => $reasonStatistics,
            'sans_motif_by_trainee' => $sansMotifByTrainee,
            'absences_by_trainee' => $absencesByTrainee,
            'daily_income' => $dailyIncome,
            'estimated_total_loss' => $estimatedTotalLoss
        ];
    }
}