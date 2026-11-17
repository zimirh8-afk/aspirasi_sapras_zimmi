<?php
$pageTitle = 'Profil - Admin';
require_once __DIR__ . '/../layouts/header.php';
require_once __DIR__ . '/../layouts/sidebar.php';

$fotoUrl = !empty($user['foto'])
    ? url('public/uploads/profil/' . $user['foto'])
    : null;
$inisial = strtoupper(substr($user['nama'] ?? 'A', 0, 1));
?>

<div class="page-header">
    <div>
        <h1>Profil Saya</h1>
        <p>Kelola informasi akun dan password kamu.</p>
    </div>
</div>

<?php if (!empty($_SESSION['success'])): ?>
    <div class="alert alert-success"><i data-lucide="check-circle" style="width:16px;height:16px;display:inline-block;vertical-align:middle"></i> <?= htmlspecialchars($_SESSION['success']) ?></div>
    <?php unset($_SESSION['success']); ?>
<?php endif; ?>
<?php if (!empty($_SESSION['error'])): ?>
    <div class="alert alert-error"><i data-lucide="alert-circle" style="width:16px;height:16px;display:inline-block;vertical-align:middle"></i> <?= htmlspecialchars($_SESSION['error']) ?></div>
    <?php unset($_SESSION['error']); ?>
<?php endif; ?>

<div style="display:flex;gap:8px;margin-bottom:20px;border-bottom:2px solid var(--gray-200)">
    <button type="button" onclick="switchTab('info')" id="tab-info" class="btn btn-primary" style="border-radius:6px 6px 0 0;margin-bottom:-2px">Informasi Akun</button>
    <button type="button" onclick="switchTab('password')" id="tab-password" class="btn btn-secondary" style="border-radius:6px 6px 0 0;margin-bottom:-2px">Ganti Password</button>
</div>

<!-- Informasi Akun -->
<div id="panel-info" class="content-card">
    <div class="section-header"><h2>Informasi Akun</h2></div>
    <form action="<?= url('profile/update') ?>" method="POST" enctype="multipart/form-data" style="padding:22px">

        <!-- Avatar -->
        <div style="display:flex;align-items:center;gap:20px;margin-bottom:24px;padding:16px;background:var(--gray-50);border-radius:var(--radius)">
            <div id="avatar-preview" style="width:72px;height:72px;border-radius:50%;background:linear-gradient(135deg,var(--primary),var(--purple));display:flex;align-items:center;justify-content:center;color:white;font-size:24px;font-weight:700;overflow:hidden;flex-shrink:0">
                <?php if ($fotoUrl): ?>
                    <img src="<?= $fotoUrl ?>" style="width:100%;height:100%;object-fit:cover">
                <?php else: ?>
                    <?= $inisial ?>
                <?php endif; ?>
            </div>
            <div>
                <label for="foto" style="cursor:pointer">
                    <span class="btn btn-secondary btn-sm"><i data-lucide="camera" style="width:14px;height:14px;display:inline-block;vertical-align:middle"></i> Ganti Foto</span>
                </label>
                <input type="file" id="foto" name="foto" accept="image/jpeg,image/png,image/webp" style="display:none" onchange="previewFoto(this)">
                <p style="font-size:12px;color:var(--gray-400);margin-top:6px">Maks. 2MB · JPG, PNG, WebP</p>
            </div>
        </div>

        <div class="form-group">
            <label>Nama Lengkap</label>
            <input type="text" name="nama" value="<?= htmlspecialchars($user['nama'] ?? '') ?>" required>
        </div>
        <div class="form-group">
            <label>Username</label>
            <input type="text" name="username" value="<?= htmlspecialchars($user['username'] ?? '') ?>" required>
        </div>
        <div class="form-group">
            <label>Role</label>
            <input type="text" value="Admin" disabled style="background:var(--gray-100);color:var(--gray-500)">
        </div>
        <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
    </form>
</div>

<!-- Ganti Password -->
<div id="panel-password" class="content-card" style="display:none">
    <div class="section-header"><h2>Ganti Password</h2></div>
    <form action="<?= url('profile/password') ?>" method="POST" style="padding:22px">
        <div class="form-group">
            <label>Password Lama</label>
            <input type="password" name="password_lama" placeholder="Masukkan password lama" required>
        </div>
        <div class="form-group">
            <label>Password Baru</label>
            <input type="password" name="password_baru" placeholder="Minimal 6 karakter" required>
        </div>
        <div class="form-group">
            <label>Konfirmasi Password Baru</label>
            <input type="password" name="password_konfirmasi" placeholder="Ulangi password baru" required>
        </div>
        <button type="submit" class="btn btn-primary">Ganti Password</button>
    </form>
</div>

<script>
function switchTab(tab) {
    document.getElementById('panel-info').style.display = tab === 'info' ? '' : 'none';
    document.getElementById('panel-password').style.display = tab === 'password' ? '' : 'none';
    document.getElementById('tab-info').className = tab === 'info' ? 'btn btn-primary' : 'btn btn-secondary';
    document.getElementById('tab-password').className = tab === 'password' ? 'btn btn-primary' : 'btn btn-secondary';
}
function previewFoto(input) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('avatar-preview').innerHTML = '<img src="' + e.target.result + '" style="width:100%;height:100%;object-fit:cover">';
        };
        reader.readAsDataURL(input.files[0]);
    }
}
</script>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
