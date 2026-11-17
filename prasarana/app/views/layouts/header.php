<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$pageTitle = $pageTitle ?? 'SIADU - Pengaduan Sarana Prasarana';
$user = $_SESSION['user'] ?? null;
$isAuth = !$user;
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($pageTitle) ?></title>
    <link rel="stylesheet" href="<?= url('public/css/style.css') ?>?v=<?= time() ?>">
    <link rel="stylesheet" href="<?= url('public/css/auth.css') ?>?v=<?= time() ?>">
    <link rel="stylesheet" href="<?= url('public/css/dashboard.css') ?>?v=<?= time() ?>">
    <link rel="stylesheet" href="<?= url('public/css/pengaduan.css') ?>?v=<?= time() ?>">
    <script src="https://unpkg.com/lucide@latest/dist/umd/lucide.min.js"></script>
</head>
<body>

<?php if ($isAuth): ?>
<div class="auth-page">
<?php else: ?>
<div class="app-wrapper">
<?php endif; ?>
