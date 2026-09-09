<?php

$pageTitle = 'Stagiaires';

require __DIR__ . '/../layouts/head.php';
require __DIR__ . '/../layouts/header.php';
?>

<main class="container py-4">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0">Liste des stagiaires</h1>
    </div>

    <div class="row g-4">

        <?php foreach ($trainees as $trainee): ?>

            <?php
            $photoPath = $trainee->getPhotoPath()
                ?? 'assets/images/placeholder-avatar.svg';
            ?>

            <div class="col-12 col-sm-6 col-md-4 col-lg-3">
                <div class="card h-100 shadow-sm">

                    <img
                        src="<?= htmlspecialchars(
                            $photoPath,
                            ENT_QUOTES,
                            'UTF-8'
                        ) ?>"
                        class="card-img-top trainee-photo"
                        alt="Photo de <?= htmlspecialchars(
                            $trainee->getFirstName() . ' ' . $trainee->getLastName(),
                            ENT_QUOTES,
                            'UTF-8'
                        ) ?>"
                    >

                    <div class="card-body">

                        <h2 class="h5 card-title">
                            <?= htmlspecialchars(
                                $trainee->getFirstName() . ' ' . $trainee->getLastName(),
                                ENT_QUOTES,
                                'UTF-8'
                            ) ?>
                        </h2>

                        <p class="card-text mb-0">
                            Identifiant AFPA :
                            <?= htmlspecialchars(
                                $trainee->getAfpaId(),
                                ENT_QUOTES,
                                'UTF-8'
                            ) ?>
                        </p>

                    </div>

                </div>
            </div>

        <?php endforeach; ?>

    </div>

</main>

<?php
require __DIR__ . '/../layouts/footer.php';
?>