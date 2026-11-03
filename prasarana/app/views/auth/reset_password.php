<?php
$pageTitle = 'Reset Password - SIADU';
require_once __DIR__ . '/../layouts/header.php';
?>

<div class="auth-container">
    <div class="auth-card">

        <div class="auth-header">
            <img src="<?= url('logo.png') ?>" alt="SIADU" style="height:80px;margin-bottom:12px;object-fit:contain">
            <h1>Reset Password</h1>
            <p>Buat password baru untuk <strong><?= htmlspecialchars($username ?? '') ?></strong></p>
        </div>

        <?php if (!empty($_SESSION['error'])): ?>
            <div class="alert alert-error">
                <i data-lucide="alert-circle" style="width:16px;height:16px;display:inline-block;vertical-align:middle"></i>
                <?= htmlspecialchars($_SESSION['error']) ?>
            </div>
            <?php unset($_SESSION['error']); ?>
        <?php endif; ?>

        <form action="<?= url('reset-password') ?>" method="POST">

            <div class="form-group">
                <label for="password">Password Baru</label>
                <div style="position:relative">
                    <input
                        type="password"
                        id="password"
                        name="password"
                        placeholder="Minimal 6 karakter"
                        autocomplete="new-password"
                        required
                        style="padding-right:44px"
                    >
                    <button type="button" onclick="togglePass('password','eye-pass')"
                        style="position:absolute;top:50%;right:12px;transform:translateY(-50%);background:none;border:none;cursor:pointer;color:#9ca3af;padding:0">
                        <i id="eye-pass" data-lucide="eye" style="width:18px;height:18px"></i>
                    </button>
                </div>
            </div>

            <div class="form-group">
                <label for="password_confirm">Konfirmasi Password</label>
                <div style="position:relative">
                    <input
                        type="password"
                        id="password_confirm"
                        name="password_confirm"
                        placeholder="Ulangi password baru"
                        autocomplete="new-password"
                        required
                        style="padding-right:44px"
                    >
                    <button type="button" onclick="togglePass('password_confirm','eye-confirm')"
                        style="position:absolute;top:50%;right:12px;transform:translateY(-50%);background:none;border:none;cursor:pointer;color:#9ca3af;padding:0">
                        <i id="eye-confirm" data-lucide="eye" style="width:18px;height:18px"></i>
                    </button>
                </div>
            </div>

            <button type="submit" class="btn btn-primary btn-full btn-lg" style="margin-top:4px">
                Simpan Password Baru
            </button>

        </form>

        <div class="auth-footer">
            <p><a href="<?= url('lupa-password') ?>">← Kembali</a></p>
        </div>

    </div>
</div>

<script>
function togglePass(inputId, iconId) {
    var input = document.getElementById(inputId);
    var icon  = document.getElementById(iconId);
    if (input.type === 'password') {
        input.type = 'text';
        icon.setAttribute('data-lucide', 'eye-off');
    } else {
        input.type = 'password';
        icon.setAttribute('data-lucide', 'eye');
    }
    if (typeof lucide !== 'undefined') lucide.createIcons();
}
</script>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
