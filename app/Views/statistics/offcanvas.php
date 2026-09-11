<?php

$totalAbsences = $statistics['total_absences'] ?? 0;

$byReason =
    $statistics['by_reason'] ?? [];

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


<!-- Total -->
<div class="mb-4">

    <div class="display-6 fw-semibold">
        <?= $totalAbsences ?>
    </div>

    <div class="text-muted">
        journées d'absence
    </div>

</div>


<!-- Répartition -->
<div class="mb-4">

    <h3 class="h6 mb-3">
        Répartition par motif
    </h3>

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


<!-- Classement -->
<div class="mb-4">

    <h3 class="h6 mb-3">
        Classement des stagiaires
    </h3>

    <?php if (empty($absencesByTrainee)): ?>

        <p class="small text-muted mb-0">
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


<!-- Perte de revenu -->
<div class="mb-4">

    <h3 class="h6 mb-3">
        Perte de revenu estimée
    </h3>

    <p class="small text-muted mb-2">
        Base : 712 € / mois · 21 jours ouvrés
    </p>

    <div class="d-flex justify-content-between py-1">

        <span>
            Revenu journalier
        </span>

        <strong>
            <?= number_format(
                $dailyIncome,
                2,
                ',',
                ' '
            ) ?>
            €
        </strong>

    </div>

    <div class="d-flex justify-content-between py-1">

        <span>
            Perte cumulée
        </span>

        <strong>
            <?= number_format(
                $estimatedTotalLoss,
                2,
                ',',
                ' '
            ) ?>
            €
        </strong>

    </div>

</div>


<?php if ($isAdmin): ?>

    <hr>

    <!-- Alerte -->
    <div>

        <h3 class="h6 mb-2">
            Règle d'alerte
        </h3>

        <p class="small text-muted">
            Nom en rouge si plus de 5 absences
            « sans motif ».
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

                <div class="d-flex justify-content-between gap-3 py-2">

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
                    </span>

                </div>

            <?php endif; ?>

        <?php endforeach; ?>


        <?php if (!$hasAlert): ?>

            <p class="small text-muted mb-0">
                Aucun stagiaire ne dépasse le seuil d'alerte.
            </p>

        <?php endif; ?>

    </div>

<?php endif; ?>