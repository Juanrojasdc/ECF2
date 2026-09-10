<?php

class Absence
{
    public function __construct(
        private int $absenceId,
        private string $absenceDate,
        private string $reason,
        private ?string $justificationPath,
        private int $traineeId
    ) {
    }

    public function getAbsenceId(): int
    {
        return $this->absenceId;
    }

    public function getAbsenceDate(): string
    {
        return $this->absenceDate;
    }

    public function getReason(): string
    {
        return $this->reason;
    }

    public function getJustificationPath(): ?string
    {
        return $this->justificationPath;
    }

    public function getTraineeId(): int
    {
        return $this->traineeId;
    }
}