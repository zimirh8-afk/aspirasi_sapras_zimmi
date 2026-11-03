<?php

$pageTitle = 'Buat Pengaduan - SIADU';

require_once __DIR__ . '/../../layouts/header.php';
require_once __DIR__ . '/../../layouts/sidebar.php';
?>

<div class="page-header">
    <div>
        <h1>Buat Pengaduan</h1>
        <p>Sampaikan laporan kerusakan sarana prasarana sekolah.</p>
    </div>
    <a href="<?= url('siswa/pengaduan') ?>" class="btn btn-secondary">← Kembali</a>
</div>

<?php if (!empty($_SESSION['error'])): ?>
    <div class="alert alert-error"><i data-lucide="alert-circle" style="width:16px;height:16px;display:inline-block;vertical-align:middle"></i> <?= htmlspecialchars($_SESSION['error']) ?></div>
    <?php unset($_SESSION['error']); ?>
<?php endif; ?>

<div class="content-card">
    <form action="<?= url('siswa/pengaduan/store') ?>" method="POST" enctype="multipart/form-data" style="padding:22px">

        <div class="form-group">
            <label for="judul">Judul Pengaduan</label>
            <input type="text" id="judul" name="judul" placeholder="Contoh: Atap kelas bocor" required>
        </div>

        <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px">
            <div class="form-group">
                <label for="kategori_id">Kategori</label>
                <select id="kategori_id" name="kategori_id" required>
                    <option value="">-- Pilih Kategori --</option>
                    <?php foreach ($kategori as $kat): ?>
                        <option value="<?= (int)$kat['id'] ?>"><?= htmlspecialchars($kat['nama']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label for="lokasi">Lokasi</label>
                <input type="text" id="lokasi" name="lokasi" placeholder="Contoh: Kelas RPL, MPLB, Akutansi, Pemasaran" required>
            </div>
        </div>

        <div class="form-group">
            <label for="deskripsi">Deskripsi Kerusakan</label>
            <textarea id="deskripsi" name="deskripsi" rows="5" placeholder="Jelaskan detail kerusakan yang terjadi..." required></textarea>
        </div>

        <div class="form-group">
            <label for="foto">Foto Kerusakan <span style="color:var(--gray-400);font-weight:400">(opsional)</span></label>
            <input type="file" id="foto" name="foto" accept="image/jpeg,image/png,image/webp">
            <p class="form-hint">Maksimal 5 MB. Format: JPG, PNG, WebP</p>
        </div>

        <div style="display:flex;gap:10px;margin-top:8px">
            <button type="submit" class="btn btn-primary"><i data-lucide="send" style="width:14px;height:14px;vertical-align:middle"></i> Kirim Pengaduan</button>
            <a href="<?= url('siswa/pengaduan') ?>" class="btn btn-secondary">Batal</a>
        </div>

    </form>
</div>

<?php require_once __DIR__ . '/../../layouts/footer.php'; ?>
