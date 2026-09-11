<?php

$pageTitle = 'Statistiques';

require __DIR__ . '/../layouts/head.php';
require __DIR__ . '/../layouts/header.php';

$totalAbsences = $statistics['total_absences'] ?? 0;
$byReason = $statistics['by_reason'] ?? [];

$absencesByTrainee =
    $statistics['absences_by_trainee'] ?? [];

$dailyIncome =
    $statistics['daily_income'] ?? 0;

$estimatedTotalLoss =
    $statistics['estimated_total_loss'] ?? 0;

$sansMotifByTrainee =
    $statistics['sans_motif_by_trainee'] ?? [];

$traineesById = [];

foreach ($trainees as $trainee) {
    $traineesById[$trainee->getTraineeId()] = $trainee;
}

?>

<main class="container py-4">

    <div class="mb-4">
        <h1 class="h3 mb-1">
            Statistiques des absences
        </h1>

        <p class="text-muted mb-0">
            Vue publique des absences enregistrées.
        </p>
    </div>


    <div class="card shadow-sm mb-4">

        <div class="card-body">

            <div class="display-6 fw-semibold">
                <?= $totalAbsences ?>
            </div>

            <div class="text-muted">
                journées d'absence
            </div>

        </div>

    </div>


    <div class="card shadow-sm">

        <div class="card-body">

            <h2 class="h5 mb-4">
                Répartition par motif
            </h2>

            <?php foreach ($byReason as $reason => $total): ?>

                <div class="d-flex justify-content-between border-bottom py-2">

                    <span>
                        <?= htmlspecialchars(
                            $reason,
                            ENT_QUOTES,
                            'UTF-8'
                        ) ?>
                    </span>

                    <strong>
                        <?= $total ?>
                    </strong>

                </div>

            <?php endforeach; ?>

        </div>

    </div>


    <div class="card shadow-sm mt-4">

        <div class="card-body">

            <h2 class="h5 mb-3">
                Classement des stagiaires
            </h2>

            <?php if (empty($absencesByTrainee)): ?>

                <p class="text-muted mb-0">
                    Aucune absence enregistrée.
                </p>

            <?php else: ?>

                <?php foreach ($absencesByTrainee as $traineeId => $total): ?>

                    <?php
                    $trainee =
                        $traineesById[$traineeId] ?? null;
                    ?>

                    <div class="d-flex justify-content-between border-bottom py-2">

                        <span>
                            <?php if ($trainee !== null): ?>

                                <?= htmlspecialchars(
                                    $trainee->getFirstName()
                                    . ' '
                                    . $trainee->getLastName(),
                                    ENT_QUOTES,
                                    'UTF-8'
                                ) ?>

                            <?php else: ?>

                                Stagiaire introuvable

                            <?php endif; ?>
                        </span>

                        <strong>
                            <?= $total ?>
                        </strong>

                    </div>

                <?php endforeach; ?>

            <?php endif; ?>

        </div>

    </div>


    <div class="card shadow-sm mt-4">

        <div class="card-body">

            <h2 class="h5 mb-3">
                Perte de revenu estimée
            </h2>

            <p class="small text-muted mb-2">
                Base de calcul :
                712 € / mois · 21 jours ouvrés
            </p>

            <p class="mb-2">
                Revenu journalier théorique :
                <strong>
                    <?= number_format(
                        $dailyIncome,
                        2,
                        ',',
                        ' '
                    ) ?>
                    €
                </strong>
            </p>

            <p class="mb-0">
                Perte cumulée estimée :
                <strong>
                    <?= number_format(
                        $estimatedTotalLoss,
                        2,
                        ',',
                        ' '
                    ) ?>
                    €
                </strong>
            </p>

        </div>

    </div>


    <?php if ($isAdmin): ?>

        <div class="card shadow-sm mt-4">

            <div class="card-body">

                <h2 class="h5 mb-3">
                    Règle d'alerte
                </h2>

                <p class="small text-muted">
                    Un stagiaire est signalé lorsqu'il dépasse
                    5 absences avec le motif « sans motif ».
                </p>

                <?php
                $hasAlert = false;
                ?>

                <?php foreach ($sansMotifByTrainee as $traineeId => $total): ?>

                    <?php if ($total > 5): ?>

                        <?php
                        $hasAlert = true;

                        $trainee =
                            $traineesById[$traineeId] ?? null;
                        ?>

                        <div class="border rounded p-3 mb-2">

                            <div class="d-flex justify-content-between align-items-center">

                                <span class="text-danger fw-semibold">

                                    <?php if ($trainee !== null): ?>

                                        <?= htmlspecialchars(
                                            $trainee->getFirstName()
                                            . ' '
                                            . $trainee->getLastName(),
                                            ENT_QUOTES,
                                            'UTF-8'
                                        ) ?>

                                    <?php else: ?>

                                        Stagiaire introuvable

                                    <?php endif; ?>

                                </span>

                                <span class="text-danger fw-semibold">
                                    <?= $total ?>
                                    absences sans motif
                                </span>

                            </div>

                        </div>

                    <?php endif; ?>

                <?php endforeach; ?>


                <?php if (!$hasAlert): ?>

                    <div class="text-muted">
                        Aucun stagiaire ne dépasse le seuil d'alerte.
                    </div>

                <?php endif; ?>

            </div>

        </div>

    <?php endif; ?>

</main>

<?php
require __DIR__ . '/../layouts/footer.php';
?>