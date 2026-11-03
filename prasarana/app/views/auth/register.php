<?php
$pageTitle = 'Daftar - SIADU';
require_once __DIR__ . '/../layouts/header.php';
?>

<div class="auth-container" style="max-width:460px">
    <div class="auth-card">

        <div class="auth-header">
            <img src="<?= url('logo.png') ?>" alt="SIADU" style="height:80px;margin-bottom:12px;object-fit:contain">
            <h1>Buat Akun Baru</h1>
            <p>Daftar sebagai siswa untuk membuat pengaduan</p>
        </div>

        <?php if (!empty($_SESSION['error'])): ?>
            <div class="alert alert-error"><i data-lucide="alert-circle" style="width:16px;height:16px;display:inline-block;vertical-align:middle"></i> <?= htmlspecialchars($_SESSION['error']) ?></div>
            <?php unset($_SESSION['error']); ?>
        <?php endif; ?>

        <form action="<?= url('register') ?>" method="POST">

            <div class="form-group">
                <label for="nama">Nama Lengkap</label>
                <input
                    type="text"
                    id="nama"
                    name="nama"
                    placeholder="Masukkan nama lengkap"
                    required
                >
            </div>

            <div class="form-group">
                <label for="nis">NIS (Nomor Induk Siswa)</label>
                <input
                    type="text"
                    id="nis"
                    name="nis"
                    placeholder="Masukkan NIS kamu"
                    required
                >
            </div>

            <div class="form-group">
                <label for="email">Email <span style="color:var(--gray-400);font-weight:400">(untuk notifikasi)</span></label>
                <input
                    type="email"
                    id="email"
                    name="email"
                    placeholder="contoh@email.com"
                >
                <small style="color:var(--gray-400);font-size:11px;margin-top:4px;display:block">
                    Email digunakan untuk menerima notifikasi status pengaduan
                </small>
            </div>

            <div class="form-group">
                <label for="username">Username</label>
                <input
                    type="text"
                    id="username"
                    name="username"
                    placeholder="Masukkan username"
                    required
                >
            </div>

            <div class="form-group">
                <label for="password">Password</label>
                <input
                    type="password"
                    id="password"
                    name="password"
                    placeholder="Minimal 6 karakter"
                    required
                >
            </div>

            <div class="form-group">
                <label for="password_confirm">Konfirmasi Password</label>
                <input
                    type="password"
                    id="password_confirm"
                    name="password_confirm"
                    placeholder="Ulangi password"
                    required
                >
            </div>

            <button type="submit" class="btn btn-primary btn-full btn-lg" style="margin-top:4px">
                Daftar Sekarang
            </button>

        </form>

        <div class="auth-footer">
            <p>Sudah punya akun? <a href="<?= url('login') ?>">Masuk di sini</a></p>
        </div>

    </div>
</div>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
