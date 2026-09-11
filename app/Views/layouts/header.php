<?php

$baseUrl = rtrim(
    dirname($_SERVER['SCRIPT_NAME']),
    '/'
);

$isAuthenticated = AuthController::isAuthenticated();
?>

<header class="border-bottom bg-white">

    <nav class="navbar navbar-expand-lg">

        <div class="container">

            <!-- Brand -->
            <a
                class="navbar-brand fw-semibold"
                href="<?= htmlspecialchars(
                    $isAuthenticated
                        ? $baseUrl . '/trainees'
                        : $baseUrl . '/login',
                    ENT_QUOTES,
                    'UTF-8'
                ) ?>"
            >
                Gestion des absences
            </a>


            <!-- Mobile button -->
            <button
                class="navbar-toggler"
                type="button"
                data-bs-toggle="collapse"
                data-bs-target="#mainNavbar"
                aria-controls="mainNavbar"
                aria-expanded="false"
                aria-label="Afficher la navigation"
            >
                <span class="navbar-toggler-icon"></span>
            </button>


            <div
                class="collapse navbar-collapse"
                id="mainNavbar"
            >

                <ul class="navbar-nav ms-auto align-items-lg-center gap-lg-2">

                    <?php if ($isAuthenticated): ?>

                        <li class="nav-item">
                            <a
                                class="nav-link"
                                href="<?= htmlspecialchars(
                                    $baseUrl . '/trainees',
                                    ENT_QUOTES,
                                    'UTF-8'
                                ) ?>"
                            >
                                Stagiaires
                            </a>
                        </li>


                        <li class="nav-item">
                            <a
                                class="nav-link"
                                href="<?= htmlspecialchars(
                                    $baseUrl . '/absences',
                                    ENT_QUOTES,
                                    'UTF-8'
                                ) ?>"
                            >
                                Absences
                            </a>
                        </li>

                    <?php endif; ?>


                    <!-- Public statistics navigation -->
                    <li class="nav-item">

                        <button
                            type="button"
                            class="btn btn-link nav-link"
                            data-bs-toggle="offcanvas"
                            data-bs-target="#statisticsOffcanvas"
                            aria-controls="statisticsOffcanvas"
                        >
                            Statistiques
                        </button>

                    </li>


                    <?php if ($isAuthenticated): ?>

                        <li class="nav-item ms-lg-2">

                            <form
                                method="POST"
                                action="<?= htmlspecialchars(
                                    $baseUrl . '/logout',
                                    ENT_QUOTES,
                                    'UTF-8'
                                ) ?>"
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

                                <button
                                    class="btn btn-outline-secondary btn-sm"
                                    type="submit"
                                >
                                    Se déconnecter
                                </button>

                            </form>

                        </li>

                    <?php else: ?>

                        <li class="nav-item ms-lg-2">

                            <a
                                class="btn btn-outline-secondary btn-sm"
                                href="<?= htmlspecialchars(
                                    $baseUrl . '/login',
                                    ENT_QUOTES,
                                    'UTF-8'
                                ) ?>"
                            >
                                Connexion
                            </a>

                        </li>

                    <?php endif; ?>

                </ul>

            </div>

        </div>

    </nav>

</header>


<!-- Statistics Offcanvas -->
<div
    class="offcanvas offcanvas-end"
    tabindex="-1"
    id="statisticsOffcanvas"
    aria-labelledby="statisticsOffcanvasLabel"
>

    <div class="offcanvas-header">

        <h2
            class="offcanvas-title h5"
            id="statisticsOffcanvasLabel"
        >
            Statistiques publiques
        </h2>

        <button
            type="button"
            class="btn-close"
            data-bs-dismiss="offcanvas"
            aria-label="Fermer"
        ></button>

    </div>


    <div
        class="offcanvas-body"
        id="statisticsOffcanvasBody"
    >

        <div class="text-muted">
            Chargement...
        </div>

    </div>

</div>