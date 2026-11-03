<?php
$pageTitle = 'Login - SIADU';
require_once __DIR__ . '/../layouts/header.php';
?>

<div class="auth-container">
    <div class="auth-card">

        <div class="auth-header">
            <img src="<?= url('logo.png') ?>" alt="SIADU" style="height:80px;margin-bottom:12px;object-fit:contain">
            <h1>Selamat Datang</h1>
            <p>Masuk ke Sistem Informasi Aduan</p>
        </div>

        <?php if (!empty($_SESSION['error'])): ?>
            <div class="alert alert-error"><i data-lucide="alert-circle" style="width:16px;height:16px;display:inline-block;vertical-align:middle"></i> <?= htmlspecialchars($_SESSION['error']) ?></div>
            <?php unset($_SESSION['error']); ?>
        <?php endif; ?>

        <?php if (!empty($_SESSION['success'])): ?>
            <div class="alert alert-success"><i data-lucide="check-circle" style="width:16px;height:16px;display:inline-block;vertical-align:middle"></i> <?= htmlspecialchars($_SESSION['success']) ?></div>
            <?php unset($_SESSION['success']); ?>
        <?php endif; ?>

        <form action="<?= url('login') ?>" method="POST">

            <div class="form-group">
                <label for="username">Username</label>
                <input
                    type="text"
                    id="username"
                    name="username"
                    placeholder="Masukkan Username Anda"
                    autocomplete="username"
                    required
                >
            </div>

            <div class="form-group">
                <label for="password">Password</label>
                <input
                    type="password"
                    id="password"
                    name="password"
                    placeholder="Masukkan password"
                    autocomplete="current-password"
                    required
                >
            </div>

            <button type="submit" class="btn btn-primary btn-full btn-lg" style="margin-top:4px">
                Masuk
            </button>

        </form>

        <div class="auth-footer">
            <p><a href="<?= url('lupa-password') ?>" style="color:#6b7280;font-size:13px">Lupa password?</a></p>
            <p>Belum punya akun? <a href="<?= url('register') ?>">Daftar sebagai siswa</a></p>
        </div>

    </div>
</div>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
