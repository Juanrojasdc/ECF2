<?php

$configPath = __DIR__ . '/config/database.php';
$sqlPath = __DIR__ . '/ecf2_juan.sql';

$success = null;
$error = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        if (!is_file($configPath)) {
            throw new RuntimeException('Configuration file not found.');
        }

        if (!is_file($sqlPath)) {
            throw new RuntimeException('SQL file not found.');
        }

        $config = require $configPath;

        $requiredKeys = [
            'host',
            'port',
            'database',
            'username',
            'password',
            'charset',
        ];

        foreach ($requiredKeys as $key) {
            if (!array_key_exists($key, $config)) {
                throw new RuntimeException('Invalid database configuration.');
            }
        }

        $databaseName = $config['database'];
        $charset = $config['charset'];

        if (
            !is_string($databaseName)
            || !preg_match('/^[a-zA-Z0-9_]+$/', $databaseName)
        ) {
            throw new RuntimeException('Invalid database name.');
        }

        if (
            !is_string($charset)
            || !preg_match('/^[a-zA-Z0-9_]+$/', $charset)
        ) {
            throw new RuntimeException('Invalid charset.');
        }

        $serverDsn = sprintf(
            'mysql:host=%s;port=%s;charset=%s',
            $config['host'],
            $config['port'],
            $charset
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

        $pdo->exec(
            sprintf(
                'CREATE DATABASE IF NOT EXISTS `%s` CHARACTER SET %s',
                $databaseName,
                $charset
            )
        );

        $pdo->exec(sprintf('USE `%s`', $databaseName));

        $sql = file_get_contents($sqlPath);

        if ($sql === false || trim($sql) === '') {
            throw new RuntimeException('SQL file is empty or unreadable.');
        }

        $pdo->exec($sql);

        $success = sprintf(
            'Installation terminée avec succès. La base de données "%s" est prête.',
            $databaseName
        );
    } catch (Throwable $exception) {
        error_log(
            sprintf(
                '[ECF INSTALL] %s in %s:%d',
                $exception->getMessage(),
                $exception->getFile(),
                $exception->getLine()
            )
        );

        $error = 'L’installation a échoué. Vérifiez la configuration MySQL, le fichier SQL et les journaux du serveur.';
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
            display: inline-block;
            padding: 10px 18px;
            border: 0;
            border-radius: 5px;
            background: #0d6efd;
            color: #ffffff;
            font-size: 16px;
            cursor: pointer;
        }

        button:hover {
            background: #0b5ed7;
        }

        code {
            background: #eeeeee;
            padding: 2px 5px;
            border-radius: 3px;
        }
    </style>
</head>

<body>
    <main class="container">
        <section class="card">
            <h1>Installation de l’application</h1>

            <p>
                Cet outil prépare automatiquement la base de données nécessaire
                au fonctionnement de l’application.
            </p>

            <?php if ($success !== null): ?>
                <div class="alert alert-success">
                    <?= htmlspecialchars($success, ENT_QUOTES, 'UTF-8') ?>
                </div>

                <div class="alert alert-warning">
                    Pour des raisons de sécurité, supprimez ou renommez
                    <code>install.php</code> après l’installation.
                </div>

                <p>
                    L’application peut maintenant être utilisée.
                </p>
            <?php elseif ($error !== null): ?>
                <div class="alert alert-danger">
                    <?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?>
                </div>

                <form method="POST">
                    <button type="submit">
                        Réessayer l’installation
                    </button>
                </form>
            <?php else: ?>
                <p>
                    L’installation va :
                </p>

                <ul>
                    <li>lire la configuration MySQL ;</li>
                    <li>se connecter au serveur MySQL ;</li>
                    <li>créer la base de données si nécessaire ;</li>
                    <li>importer le fichier <code>ecf2_juan.sql</code>.</li>
                </ul>

                <div class="alert alert-warning">
                    Vérifiez que MySQL est démarré avant de lancer l’installation.
                </div>

                <form method="POST">
                    <button type="submit">
                        Lancer l’installation
                    </button>
                </form>
            <?php endif; ?>
        </section>
    </main>
</body>
</html>