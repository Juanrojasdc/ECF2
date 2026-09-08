<?php
/** @var Trainee[] $trainees */ // Esto es un comentario de tipo para ayudar a los IDEs a entender que $trainees es un array de objetos Trainee. Esto facilita la autocompletación y la detección de errores en el código.
?>


<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Stagiaires</title>
</head>

<body>

    <h1>Liste des stagiaires</h1>

    <?php foreach ($trainees as $trainee): ?>
        <p>
            <?= htmlspecialchars(
                $trainee->getFirstName() . ' ' . $trainee->getLastName(),
                ENT_QUOTES,
                'UTF-8'
            ) ?>
        </p>
    <?php endforeach; ?>

</body>
</html>
