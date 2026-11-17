<?php
$pageTitle = 'Lupa Password - SIADU';
require_once __DIR__ . '/../layouts/header.php';
?>

<div class="auth-container">
    <div class="auth-card">

        <div class="auth-header">
            <img src="<?= url('logo.png') ?>" alt="SIADU" style="height:80px;margin-bottom:12px;object-fit:contain">
            <h1>Lupa Password</h1>
            <p>Masukkan username akunmu untuk melanjutkan</p>
        </div>

        <?php if (!empty($_SESSION['error'])): ?>
            <div class="alert alert-error">
                <i data-lucide="alert-circle" style="width:16px;height:16px;display:inline-block;vertical-align:middle"></i>
                <?= htmlspecialchars($_SESSION['error']) ?>
            </div>
            <?php unset($_SESSION['error']); ?>
        <?php endif; ?>

        <form action="<?= url('lupa-password/cari') ?>" method="POST">

            <div class="form-group">
                <label for="username">Username</label>
                <input
                    type="text"
                    id="username"
                    name="username"
                    placeholder="Masukkan username"
                    autocomplete="username"
                    required
                >
            </div>

            <button type="submit" class="btn btn-primary btn-full btn-lg" style="margin-top:4px">
                Lanjutkan
            </button>

        </form>

        <div class="auth-footer">
            <p>Ingat password? <a href="<?= url('login') ?>">Kembali ke Login</a></p>
        </div>

    </div>
</div>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
