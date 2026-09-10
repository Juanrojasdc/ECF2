<?php

$pageTitle = 'Ajouter une absence';
$error = $error ?? null;

require __DIR__ . '/../layouts/head.php';
require __DIR__ . '/../layouts/header.php';
?>

<main class="container py-4">

    <div class="row justify-content-center">

        <div class="col-12 col-md-9 col-lg-7">

            <div class="mb-4">
                <h1 class="h3 mb-1">
                    Ajouter une absence
                </h1>

                <p class="text-muted mb-0">
                    Une absence correspond à une journée complète.
                </p>
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


            <div class="card shadow-sm">

                <div class="card-body p-4">

                    <h2 class="h5 mb-1">
                        Ajouter une absence
                    </h2>

                    <p class="small text-muted mb-4">
                        Renseignez les informations de la journée d'absence.
                    </p>


                    <form
                        method="POST"
                        action="create"
                    >

                        <input
                            type="hidden"
                            name="csrf_token"
                            value="<?= htmlspecialchars(
                                Csrf::token(),
                                ENT_QUOTES,
                                'UTF-8'
                            ) ?>"
                        >


                        <!-- Stagiaire -->
                        <div class="mb-3">

                            <label
                                for="trainee_id"
                                class="form-label"
                            >
                                Stagiaire
                            </label>

                            <select
                                id="trainee_id"
                                name="trainee_id"
                                class="form-select"
                                required
                            >

                                <option value="">
                                    Sélectionner un stagiaire
                                </option>

                                <?php foreach ($trainees as $trainee): ?>

                                    <option
                                        value="<?= $trainee->getTraineeId() ?>"
                                        <?= (int) ($_POST['trainee_id'] ?? 0) === $trainee->getTraineeId()
                                            ? 'selected'
                                            : '' ?>
                                    >
                                        <?= htmlspecialchars(
                                            $trainee->getFirstName()
                                            . ' '
                                            . $trainee->getLastName(),
                                            ENT_QUOTES,
                                            'UTF-8'
                                        ) ?>
                                    </option>

                                <?php endforeach; ?>

                            </select>

                        </div>


                        <div class="row">

                            <!-- Date -->
                            <div class="col-12 col-md-6 mb-3">

                                <label
                                    for="absence_date"
                                    class="form-label"
                                >
                                    Date
                                </label>

                                <input
                                    type="date"
                                    id="absence_date"
                                    name="absence_date"
                                    class="form-control"
                                    value="<?= htmlspecialchars(
                                        $_POST['absence_date'] ?? '',
                                        ENT_QUOTES,
                                        'UTF-8'
                                    ) ?>"
                                    required
                                >

                            </div>


                            <!-- Motif -->
                            <div class="col-12 col-md-6 mb-3">

                                <label
                                    for="reason"
                                    class="form-label"
                                >
                                    Motif
                                </label>

                                <select
                                    id="reason"
                                    name="reason"
                                    class="form-select"
                                    required
                                >

                                    <option value="">
                                        Sélectionner un motif
                                    </option>

                                    <?php
                                    $reasons = [
                                        'maladie',
                                        'sans motif',
                                        'absence légale',
                                        'accident du travail'
                                    ];

                                    $selectedReason =
                                        $_POST['reason'] ?? '';
                                    ?>

                                    <?php foreach ($reasons as $reason): ?>

                                        <option
                                            value="<?= htmlspecialchars(
                                                $reason,
                                                ENT_QUOTES,
                                                'UTF-8'
                                            ) ?>"
                                            <?= $selectedReason === $reason
                                                ? 'selected'
                                                : '' ?>
                                        >
                                            <?= htmlspecialchars(
                                                $reason,
                                                ENT_QUOTES,
                                                'UTF-8'
                                            ) ?>
                                        </option>

                                    <?php endforeach; ?>

                                </select>

                            </div>

                        </div>


                        <!-- Futur justificatif PDF -->
                        <div class="border rounded p-3 mb-4 bg-light">

                            <div class="fw-semibold">
                                Justificatif PDF
                                <span class="fw-normal text-muted">
                                    (optionnel)
                                </span>
                            </div>

                            <div class="small text-muted">
                                Le dépôt d'un justificatif sera disponible prochainement.
                            </div>

                        </div>


                        <div class="d-flex flex-column flex-sm-row gap-2">

                            <a
                                href="../absences"
                                class="btn btn-outline-secondary flex-sm-fill"
                            >
                                Annuler
                            </a>

                            <button
                                type="submit"
                                class="btn btn-primary flex-sm-fill"
                            >
                                Enregistrer l'absence
                            </button>

                        </div>

                    </form>

                </div>

            </div>


            <p class="small text-muted mt-3">
                Pas de retard · pas de demi-journée · une absence correspond à une journée complète.
            </p>

        </div>

    </div>

</main>

<?php
require __DIR__ . '/../layouts/footer.php';
?>