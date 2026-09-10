<?php

$pageTitle = 'Absences';

require __DIR__ . '/../layouts/head.php';
require __DIR__ . '/../layouts/header.php';

$flashMessage = $_SESSION['flash_message'] ?? null;

unset($_SESSION['flash_message']);

/*
 * Mapa de stagiaires:
 * trainee_id => objet Trainee
 *
 * Esto nos permite recuperar rápidamente el nombre
 * correspondiente a cada ausencia.
 */
$traineesById = [];

foreach ($trainees as $trainee) {
    $traineesById[$trainee->getTraineeId()] = $trainee;
}
?>

<main class="container py-4">

    <?php if ($flashMessage !== null): ?>
        <div class="alert alert-success">
            <?= htmlspecialchars(
                $flashMessage,
                ENT_QUOTES,
                'UTF-8'
            ) ?>
        </div>
    <?php endif; ?>


    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">

        <div>
            <h1 class="h3 mb-1">
                Gestion des absences
            </h1>

            <p class="text-muted mb-0">
                Historique des journées
            </p>
        </div>


        <div class="d-flex flex-wrap gap-2">

            <a
                href="absences/create"
                class="btn btn-primary"
            >
                Ajouter une absence
            </a>

            <button
                type="button"
                id="managementModeButton"
                class="btn btn-outline-secondary"
            >
                Mode gestion
            </button>

        </div>

    </div>


    <div class="mb-3">

        <h2 class="h5 mb-1">
            Historique des journées
        </h2>

        <p class="small text-muted mb-0">
            Consultez et gérez les absences enregistrées.
        </p>

    </div>


    <?php if (empty($absences)): ?>

        <div class="alert alert-info">
            Aucune absence enregistrée.
        </div>

    <?php else: ?>

        <div class="d-flex flex-column gap-2">

            <?php foreach ($absences as $absence): ?>

                <?php
                $trainee = $traineesById[
                    $absence->getTraineeId()
                ] ?? null;

                $traineeName = $trainee !== null
                    ? $trainee->getFirstName() . ' ' . $trainee->getLastName()
                    : 'Stagiaire introuvable';

                $date = DateTime::createFromFormat(
                    'Y-m-d',
                    $absence->getAbsenceDate()
                );

                $formattedDate = $date
                    ? $date->format('d/m/Y')
                    : $absence->getAbsenceDate();
                ?>


                <div class="border rounded bg-white px-3 py-3">

                    <div class="row align-items-center g-3">

                        <!-- Date -->
                        <div class="col-12 col-sm-3 col-lg-2">

                            <span class="small text-muted d-sm-none">
                                Date
                            </span>

                            <div>
                                <?= htmlspecialchars(
                                    $formattedDate,
                                    ENT_QUOTES,
                                    'UTF-8'
                                ) ?>
                            </div>

                        </div>


                        <!-- Stagiaire + motif -->
                        <div class="col-12 col-sm-5 col-lg-6">

                            <div class="fw-semibold">
                                <?= htmlspecialchars(
                                    $traineeName,
                                    ENT_QUOTES,
                                    'UTF-8'
                                ) ?>
                            </div>

                            <div class="small text-muted">
                                <?= htmlspecialchars(
                                    $absence->getReason(),
                                    ENT_QUOTES,
                                    'UTF-8'
                                ) ?>
                            </div>

                        </div>


                        <!-- Justificatif -->
                        <div class="col-6 col-sm-2 text-sm-end">

                            <?php if (
        $absence->getJustificationPath() !== null
    ): ?>

        <a
            href="absences/justification?id=<?= $absence->getAbsenceId() ?>"
            class="btn btn-sm btn-outline-secondary"
            target="_blank"
            rel="noopener noreferrer"
        >
            PDF
        </a>

    <?php else: ?>

        <span class="small text-muted">
            —
        </span>

    <?php endif; ?>

                        </div>


                        <!-- Actions -->
                        <div class="col-6 col-sm-2 text-end">

                            <div class="management-actions d-none">

                                <div class="d-flex flex-column flex-lg-row justify-content-end gap-1">

                                    <a
                                        href="absences/edit?id=<?= $absence->getAbsenceId() ?>"
                                        class="btn btn-sm btn-outline-warning"
                                    >
                                        Modifier
                                    </a>


                                    <form
                                        method="POST"
                                        action="absences/delete"
                                        onsubmit="return confirm(
                                            'Supprimer cette absence ? Cette action est irréversible.'
                                        );"
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

                                        <input
                                            type="hidden"
                                            name="absence_id"
                                            value="<?= $absence->getAbsenceId() ?>"
                                        >

                                        <button
                                            type="submit"
                                            class="btn btn-sm btn-outline-danger w-100"
                                        >
                                            Supprimer
                                        </button>

                                    </form>

                                </div>

                            </div>

                        </div>

                    </div>

                </div>

            <?php endforeach; ?>

        </div>

    <?php endif; ?>

</main>

<?php
require __DIR__ . '/../layouts/footer.php';
?>