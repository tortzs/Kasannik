<?php
/* @var var $content */
$title = $title ?? 'Kasannik';
?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <title><?= htmlspecialchars($title) ?></title>

    <link rel="stylesheet" href="/assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
<script>
    (function() {
        const savedTheme = localStorage.getItem('kasannik-theme');
        const systemPrefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;

        if (savedTheme === 'dark' || (!savedTheme && systemPrefersDark)) {
            document.body.classList.add('dark-theme');
        }
    })();
</script>

<?php require APP_PATH . '/views/layouts/header.php'; ?>

<main class="page-content">
    <?= $content ?>
</main>

<?php require APP_PATH . '/views/layouts/footer.php'; ?>
</body>
</html>