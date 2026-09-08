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

//Los atributos de la clase Trainee son privados y se acceden a través de métodos públicos (getters) para mantener el principio de encapsulación y proteger la integridad de los datos. Esto permite controlar cómo se accede y modifica la información del objeto, evitando cambios no deseados desde fuera de la clase.

//Los atributos con tipo de dato nullable (por ejemplo, ?string) permiten que esos campos puedan ser nulos, lo que es útil para representar información opcional o desconocida. Esto proporciona flexibilidad al manejar datos incompletos o ausentes sin causar errores en la aplicación.

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