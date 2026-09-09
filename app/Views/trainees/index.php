<?php

$pageTitle = 'Stagiaires';

require __DIR__ . '/../layouts/head.php';
require __DIR__ . '/../layouts/header.php';

$flashMessage = $_SESSION['flash_message'] ?? null;

unset($_SESSION['flash_message']);
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

 <div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0">Liste des stagiaires</h1>

    <div class="d-flex gap-2">
        <a href="trainees/create" class="btn btn-primary">
            Ajouter un stagiaire
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

                   <div class="mt-3">

    <a href="#" class="btn btn-sm btn-outline-primary">
        Voir
    </a>

    <div class="management-actions d-inline-block d-none">

       <a
    href="trainees/edit?id=<?= $trainee->getTraineeId() ?>"
    class="btn btn-sm btn-outline-warning"
>
    Modifier
</a>

       <form
    method="POST"
    action="trainees/delete"
    class="d-inline"
    onsubmit="return confirm(
        'Supprimer ce stagiaire ? Cette action est irréversible.'
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
        name="trainee_id"
        value="<?= $trainee->getTraineeId() ?>"
    >

    <button
        type="submit"
        class="btn btn-sm btn-outline-danger"
    >
        Supprimer
    </button>
</form>

    </div>

</div>

                </div>
            </div>

        <?php endforeach; ?>

    </div>

</main>

<?php
require __DIR__ . '/../layouts/footer.php';
?>