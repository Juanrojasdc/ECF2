<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion administrateur</title>
</head>
<body>
<?php
$flashMessage = $_SESSION['flash_message'] ?? null;
unset($_SESSION['flash_message']);
?>
<!-- mensaje flash por si alguien intenta acceder a una página protegida o no está autenticado -->
<?php if ($flashMessage !== null): ?>
    <p>
        <?= htmlspecialchars($flashMessage, ENT_QUOTES, 'UTF-8') ?>
    </p>
<?php endif; ?>
    <h1>Connexion administrateur</h1>

    <?php if ($error !== null): ?>
        <p>
            <?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?>
        </p>
    <?php endif; ?>

    <form method="POST" action="login">
        <div>
            <label for="login">Identifiant</label>
            <input
                type="text"
                id="login"
                name="login"
                required
            >
        </div>

        <div>
            <label for="password">Mot de passe</label>
            <input
                type="password"
                id="password"
                name="password"
                required
            >
        </div>

        <button type="submit">Se connecter</button>
    </form>

</body>
</html>