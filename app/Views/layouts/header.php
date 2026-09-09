<header class="border-bottom bg-white">
    <nav class="navbar navbar-expand-lg">
        <div class="container">
            <a class="navbar-brand fw-semibold" href="trainees">
                Gestion des absences
            </a>

            <?php if (AuthController::isAuthenticated()): ?>
                <form method="POST" action="logout">
                    <input
                        type="hidden"
                        name="csrf_token"
                        value="<?= htmlspecialchars(
                            Csrf::token(),
                            ENT_QUOTES,
                            'UTF-8'
                        ) ?>"
                    >

                    <button class="btn btn-outline-secondary btn-sm" type="submit">
                        Se déconnecter
                    </button>
                </form>
            <?php endif; ?>
        </div>
    </nav>
</header>