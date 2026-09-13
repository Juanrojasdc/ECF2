<?php

$config = [
    'host' => 'localhost',
    'port' => '3306',
    'database' => 'ecf2_juan',
    'username' => 'root',
    'password' => 'root',
    'charset' => 'utf8mb4',
];

$siteZipPath = __DIR__ . '/ECF2_site.zip';
$sqlPath = __DIR__ . '/ecf2_juan.sql';
$installPath = __FILE__;

$success = null;
$error = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        if (!is_file($siteZipPath)) {
            throw new RuntimeException('Le fichier ECF2_site.zip est introuvable.');
        }

        if (!is_file($sqlPath)) {
            throw new RuntimeException('Le fichier ecf2_juan.sql est introuvable.');
        }

        if (!class_exists('ZipArchive')) {
            throw new RuntimeException('L’extension PHP ZipArchive n’est pas disponible.');
        }

        $sql = file_get_contents($sqlPath);

        if ($sql === false || trim($sql) === '') {
            throw new RuntimeException('Le fichier SQL est vide ou illisible.');
        }

        $serverDsn = sprintf(
            'mysql:host=%s;port=%s;charset=%s',
            $config['host'],
            $config['port'],
            $config['charset']
        );

        $pdo = new PDO(
            $serverDsn,
            $config['username'],
            $config['password'],
            [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::MYSQL_ATTR_MULTI_STATEMENTS => true,
            ]
        );

        $zip = new ZipArchive();

        if ($zip->open($siteZipPath) !== true) {
            throw new RuntimeException('Le ZIP du site ne peut pas être ouvert.');
        }

        $entries = [];

        for ($index = 0; $index < $zip->numFiles; $index++) {
            $entry = str_replace('\\', '/', $zip->getNameIndex($index));
            $parts = array_values(array_filter(explode('/', $entry), 'strlen'));

            if (
                $entry === ''
                || str_starts_with($entry, '/')
                || preg_match('/^[a-zA-Z]:/', $entry)
                || in_array('..', $parts, true)
            ) {
                $zip->close();
                throw new RuntimeException('Le ZIP du site contient une route invalide.');
            }

            $entries[] = $entry;
        }

        $hasApp = count(array_filter(
            $entries,
            static fn (string $entry): bool => str_starts_with($entry, 'app/')
        )) > 0;

        if (
            !$hasApp
            || !in_array('config/database.php', $entries, true)
            || !in_array('public/index.php', $entries, true)
        ) {
            $zip->close();
            throw new RuntimeException('La structure de ECF2_site.zip est invalide.');
        }

        if (!$zip->extractTo(__DIR__)) {
            $zip->close();
            throw new RuntimeException('L’extraction du site a échoué.');
        }

        $zip->close();

        if (
            !is_dir(__DIR__ . '/app')
            || !is_file(__DIR__ . '/config/database.php')
            || !is_file(__DIR__ . '/public/index.php')
        ) {
            throw new RuntimeException('Le site extrait est incomplet.');
        }

        $databaseName = $config['database'];
        $charset = $config['charset'];

        $pdo->exec(sprintf(
            'CREATE DATABASE IF NOT EXISTS `%s` CHARACTER SET %s',
            $databaseName,
            $charset
        ));

        $pdo->exec(sprintf('USE `%s`', $databaseName));
        $pdo->exec($sql);

        foreach ([$siteZipPath, $sqlPath, $installPath] as $filePath) {
            if (!is_writable($filePath)) {
                throw new RuntimeException(
                    'L’installation est terminée, mais les fichiers d’installation ne peuvent pas être supprimés.'
                );
            }
        }

        if (!unlink($siteZipPath) || !unlink($sqlPath) || !unlink($installPath)) {
            throw new RuntimeException(
                'L’installation est terminée, mais le nettoyage automatique a échoué.'
            );
        }

        $success = sprintf(
            'Installation terminée. La base « %s » est prête et les fichiers d’installation ont été supprimés.',
            $databaseName
        );
    } catch (Throwable $exception) {
        error_log(sprintf('[ECF INSTALL] %s', $exception->getMessage()));
        $error = $exception->getMessage();
    }
}

?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Installation - Gestion des absences</title>
    <style>
        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background: #f5f5f5;
            color: #212529;
        }

        .container {
            max-width: 720px;
            margin: 60px auto;
            padding: 0 20px;
        }

        .card {
            background: #ffffff;
            border: 1px solid #dddddd;
            border-radius: 8px;
            padding: 32px;
        }

        h1 {
            margin-top: 0;
        }

        .alert {
            padding: 16px;
            margin-bottom: 20px;
            border-radius: 6px;
        }

        .alert-success {
            background: #d1e7dd;
            border: 1px solid #badbcc;
            color: #0f5132;
        }

        .alert-danger {
            background: #f8d7da;
            border: 1px solid #f5c2c7;
            color: #842029;
        }

        .alert-warning {
            background: #fff3cd;
            border: 1px solid #ffecb5;
            color: #664d03;
        }

        button {
            padding: 10px 18px;
            border: 0;
            border-radius: 5px;
            background: #0d6efd;
            color: #ffffff;
            font-size: 16px;
            cursor: pointer;
        }
    </style>
</head>
<body>
    <main class="container">
        <section class="card">
            <h1>Installation de l’application</h1>

            <?php if ($success !== null): ?>
                <div class="alert alert-success">
                    <?= htmlspecialchars($success, ENT_QUOTES, 'UTF-8') ?>
                </div>

                <p>L’application peut maintenant être utilisée.</p>
            <?php elseif ($error !== null): ?>
                <div class="alert alert-danger">
                    <?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?>
                </div>

                <form method="POST">
                    <button type="submit">Réessayer l’installation</button>
                </form>
            <?php else: ?>
                <p>L’installation va :</p>

                <ul>
                    <li>vérifier les fichiers et la connexion MySQL ;</li>
                    <li>décompresser automatiquement le site ;</li>
                    <li>créer la base et importer les données ;</li>
                    <li>supprimer les fichiers d’installation après le succès.</li>
                </ul>

                <div class="alert alert-warning">
                    Vérifiez que MySQL est démarré avant de continuer.
                </div>

                <form method="POST">
                    <button type="submit">Lancer l’installation</button>
                </form>
            <?php endif; ?>
        </section>
    </main>
</body>
</html>
