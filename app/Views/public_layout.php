<?php

$pageTitle = $title ?? 'Student-Lecturer Platform';
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= e($pageTitle) ?> - ClassBridge</title>
    <link rel="icon" type="image/png" href="<?= e(asset('img/classbridge-logo.png')) ?>">
    <link rel="apple-touch-icon" href="<?= e(asset('img/classbridge-logo.png')) ?>">
    <link rel="stylesheet" href="<?= e(asset('css/style.css')) ?>">
</head>
<body class="public-body">
    <main class="public-main">
        <?php if ($message = flash('success')): ?>
            <div class="notice success public-notice"><?= e($message) ?></div>
        <?php endif; ?>
        <?php if ($message = flash('error')): ?>
            <div class="notice error public-notice"><?= e($message) ?></div>
        <?php endif; ?>

        <?php require $viewPath; ?>
    </main>
    <script src="<?= e(asset('js/app.js')) ?>"></script>
</body>
</html>
