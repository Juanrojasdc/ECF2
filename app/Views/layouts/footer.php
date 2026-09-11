<footer class="mt-auto border-top py-3 text-center text-body-secondary">
    <small>Gestion des absences — AFPA</small>
</footer>

<script
    src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js">
</script>

<?php
$baseUrl = rtrim(
    dirname($_SERVER['SCRIPT_NAME']),
    '/'
);
?>

<script
    src="<?= htmlspecialchars(
        $baseUrl . '/assets/js/app.js',
        ENT_QUOTES,
        'UTF-8'
    ) ?>"
    data-base-url="<?= htmlspecialchars(
        $baseUrl,
        ENT_QUOTES,
        'UTF-8'
    ) ?>"
></script>

</body>
</html>