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
</head>
<body>

<?php require APP_PATH . '/views/layouts/header.php'; ?>

<main class="page-content">
    <?= $content ?>
</main>

<?php require APP_PATH . '/views/layouts/footer.php'; ?>
</body>
</html>