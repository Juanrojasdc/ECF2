<?php
$pageTitle = 'Connexion administrateur';
require __DIR__ . '/../layouts/head.php';

$flashMessage = $_SESSION['flash_message'] ?? null;
unset($_SESSION['flash_message']);
?>
<main class="container flex-grow-1 d-flex align-items-center justify-content-center py-4">
<section class="card login-card shadow-sm" aria-labelledby="login-title">
<div class="card-body p-4">
<?php if ($flashMessage !== null): ?>
    <div class="alert alert-info" role="status">
        <?= htmlspecialchars($flashMessage, ENT_QUOTES, 'UTF-8') ?>
    </div>
<?php endif; ?>
    <h1 id="login-title" class="h4 mb-4">Connexion administrateur</h1>

    <?php if ($error !== null): ?>
        <div class="alert alert-danger" role="alert">
            <?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?>
        </div>
    <?php endif; ?>

    <form method="POST" action="login">
        <div class="mb-3">
            <label class="form-label" for="login">Identifiant</label>
            <input
                class="form-control"
                autocomplete="username"
                type="text"
                id="login"
                name="login"
                required
            >
        </div>

        <div class="mb-4">
            <label class="form-label" for="password">Mot de passe</label>
            <input
                class="form-control"
                autocomplete="current-password"
                type="password"
                id="password"
                name="password"
                required
            >
        </div>

        <button class="btn btn-primary w-100" type="submit">Se connecter</button>
    </form>
</div>
</section>
</main>
<?php require __DIR__ . '/../layouts/footer.php'; ?>
