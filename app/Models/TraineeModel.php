<?php

class Trainee
{
    public function __construct(
        private int $traineeId,
        private string $afpaId,
        private string $firstName,
        private string $lastName,
        private ?string $personalEmail,
        private ?string $phone,
        private ?string $professionalUrl,
        private ?string $professionalEmail,
        private ?string $residence,
        private ?string $birthDate,
        private ?string $photoPath
    ) {
    }





    public function getTraineeId(): int
    {
        return $this->traineeId;
    }

    public function getAfpaId(): string
    {
        return $this->afpaId;
    }

    public function getFirstName(): string
    {
        return $this->firstName;
    }

    public function getLastName(): string
    {
        return $this->lastName;
    }

    public function getPersonalEmail(): ?string
    {
        return $this->personalEmail;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function getProfessionalUrl(): ?string
    {
        return $this->professionalUrl;
    }

    public function getProfessionalEmail(): ?string
    {
        return $this->professionalEmail;
    }

    public function getResidence(): ?string
    {
        return $this->residence;
    }

    public function getBirthDate(): ?string
    {
        return $this->birthDate;
    }

    public function getPhotoPath(): ?string
    {
        return $this->photoPath;
    }
}