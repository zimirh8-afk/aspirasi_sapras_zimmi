<?php
$pageTitle = 'Edit Pengaduan - SIADU';
require_once __DIR__ . '/../../layouts/header.php';
require_once __DIR__ . '/../../layouts/sidebar.php';
?>

<div class="page-header">
    <div>
        <h1>Edit Pengaduan</h1>
        <p>Hanya pengaduan berstatus <strong>Diajukan</strong> yang bisa diedit.</p>
    </div>
    <a href="<?= url('siswa/pengaduan') ?>" class="btn btn-secondary">← Kembali</a>
</div>

<?php if (!empty($_SESSION['error'])): ?>
    <div class="alert alert-error"><i data-lucide="alert-circle" style="width:16px;height:16px;display:inline-block;vertical-align:middle"></i> <?= htmlspecialchars($_SESSION['error']) ?></div>
    <?php unset($_SESSION['error']); ?>
<?php endif; ?>

<div class="content-card">
    <form action="<?= url('siswa/pengaduan/edit/' . $pengaduan['id']) ?>" method="POST" style="padding:22px">

        <div class="form-group">
            <label>Judul Pengaduan</label>
            <input type="text" name="judul" value="<?= htmlspecialchars($pengaduan['judul']) ?>" required>
        </div>

        <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px">
            <div class="form-group">
                <label>Kategori</label>
                <select name="kategori_id" required>
                    <option value="">-- Pilih Kategori --</option>
                    <?php foreach ($kategori as $kat): ?>
                        <option value="<?= (int)$kat['id'] ?>" <?= $pengaduan['kategori_id'] == $kat['id'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($kat['nama']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label>Lokasi</label>
                <input type="text" name="lokasi" value="<?= htmlspecialchars($pengaduan['lokasi']) ?>" required>
            </div>
        </div>

        <div class="form-group">
            <label>Deskripsi Kerusakan</label>
            <textarea name="deskripsi" rows="5" required><?= htmlspecialchars($pengaduan['deskripsi']) ?></textarea>
        </div>

        <div style="display:flex;gap:10px">
            <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
            <a href="<?= url('siswa/pengaduan') ?>" class="btn btn-secondary">Batal</a>
        </div>

    </form>
</div>

<?php require_once __DIR__ . '/../../layouts/footer.php'; ?>
