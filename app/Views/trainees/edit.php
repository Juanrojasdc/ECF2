<?php

$pageTitle = 'Modifier un stagiaire';
$error = $error ?? null;

require __DIR__ . '/../layouts/head.php';
require __DIR__ . '/../layouts/header.php';
?>

<main class="container py-4">

    <div class="mb-4">
        <h1 class="h3">Modifier un stagiaire</h1>

        <a href="../trainees">
            Retour au trombinoscope
        </a>
    </div>

    <?php if ($error !== null): ?>
        <div class="alert alert-danger">
            <?= htmlspecialchars(
                $error,
                ENT_QUOTES,
                'UTF-8'
            ) ?>
        </div>
    <?php endif; ?>

    <form method="POST" action="edit" enctype="multipart/form-data">

        <input
            type="hidden"
            name="csrf_token"
            value="<?= htmlspecialchars(
                Csrf::token(),
                ENT_QUOTES,
                'UTF-8'
            ) ?>"
        >

        <input
            type="hidden"
            name="trainee_id"
            value="<?= $trainee->getTraineeId() ?>"
        >

        <div class="row g-3">

            <div class="col-md-4">
                <label for="afpa_id" class="form-label">
                    Identifiant AFPA *
                </label>

                <input
                    type="text"
                    class="form-control"
                    id="afpa_id"
                    name="afpa_id"
                    value="<?= htmlspecialchars(
                        $trainee->getAfpaId(),
                        ENT_QUOTES,
                        'UTF-8'
                    ) ?>"
                    required
                >
            </div>

            <div class="col-md-4">
                <label for="first_name" class="form-label">
                    Prénom *
                </label>

                <input
                    type="text"
                    class="form-control"
                    id="first_name"
                    name="first_name"
                    value="<?= htmlspecialchars(
                        $trainee->getFirstName(),
                        ENT_QUOTES,
                        'UTF-8'
                    ) ?>"
                    required
                >
            </div>

            <div class="col-md-4">
                <label for="last_name" class="form-label">
                    Nom *
                </label>

                <input
                    type="text"
                    class="form-control"
                    id="last_name"
                    name="last_name"
                    value="<?= htmlspecialchars(
                        $trainee->getLastName(),
                        ENT_QUOTES,
                        'UTF-8'
                    ) ?>"
                    required
                >
            </div>

            <div class="col-md-6">
                <label for="personal_email" class="form-label">
                    Email personnel
                </label>

                <input
                    type="email"
                    class="form-control"
                    id="personal_email"
                    name="personal_email"
                    value="<?= htmlspecialchars(
                        $trainee->getPersonalEmail() ?? '',
                        ENT_QUOTES,
                        'UTF-8'
                    ) ?>"
                >
            </div>

            <div class="col-md-6">
                <label for="professional_email" class="form-label">
                    Email professionnel
                </label>

                <input
                    type="email"
                    class="form-control"
                    id="professional_email"
                    name="professional_email"
                    value="<?= htmlspecialchars(
                        $trainee->getProfessionalEmail() ?? '',
                        ENT_QUOTES,
                        'UTF-8'
                    ) ?>"
                >
            </div>

            <div class="col-md-6">
                <label for="phone" class="form-label">
                    Téléphone
                </label>

                <input
                    type="text"
                    class="form-control"
                    id="phone"
                    name="phone"
                    value="<?= htmlspecialchars(
                        $trainee->getPhone() ?? '',
                        ENT_QUOTES,
                        'UTF-8'
                    ) ?>"
                >
            </div>

            <div class="col-md-6">
                <label for="professional_url" class="form-label">
                    URL professionnelle
                </label>

                <input
                    type="url"
                    class="form-control"
                    id="professional_url"
                    name="professional_url"
                    value="<?= htmlspecialchars(
                        $trainee->getProfessionalUrl() ?? '',
                        ENT_QUOTES,
                        'UTF-8'
                    ) ?>"
                >
            </div>

            <div class="col-md-6">
                <label for="residence" class="form-label">
                    Lieu de résidence
                </label>

                <input
                    type="text"
                    class="form-control"
                    id="residence"
                    name="residence"
                    value="<?= htmlspecialchars(
                        $trainee->getResidence() ?? '',
                        ENT_QUOTES,
                        'UTF-8'
                    ) ?>"
                >
            </div>

            <div class="col-md-6">
                <label for="birth_date" class="form-label">
                    Date de naissance
                </label>

                <input
                    type="date"
                    class="form-control"
                    id="birth_date"
                    name="birth_date"
                    value="<?= htmlspecialchars(
                        $trainee->getBirthDate() ?? '',
                        ENT_QUOTES,
                        'UTF-8'
                    ) ?>"
                >
            </div>

            <div class="col-md-6">
    <label for="photo" class="form-label">
        Nouvelle photo
    </label>

    <input
        type="file"
        class="form-control"
        id="photo"
        name="photo"
        accept=".jpg,.jpeg,.png,.webp"
    >
</div>

        </div>

        <div class="mt-4">

            <button
                type="submit"
                class="btn btn-warning"
            >
                Enregistrer les modifications
            </button>

        </div>

    </form>

</main>

<?php
require __DIR__ . '/../layouts/footer.php';
?>