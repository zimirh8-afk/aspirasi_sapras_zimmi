<?php
$pageTitle = 'Detail Pengaduan - Admin';
require_once __DIR__ . '/../../layouts/header.php';
require_once __DIR__ . '/../../layouts/sidebar.php';

$statusClass = match($pengaduan['status'] ?? '') {
    'Diajukan' => 'badge-diajukan',
    'Diproses' => 'badge-diproses',
    'Selesai'  => 'badge-selesai',
    'Ditolak'  => 'badge-ditolak',
    default    => ''
};

// Ambil NIS pelapor (aspirasi.user_id = siswa.id_siswa)
$siswaModel = new Siswa();
$siswaData  = $siswaModel->findByIdSiswa($pengaduan['user_id'] ?? 0);
$nisPelapor = $siswaData['nis'] ?? '-';
?>

<div class="page-header">
    <div>
        <h1>Detail Pengaduan</h1>
        <p style="color:var(--gray-400);font-size:13px">#<?= $pengaduan['id'] ?></p>
    </div>
    <div style="display:flex;gap:8px">
        <button onclick="window.print()" class="btn btn-secondary no-print">
            <i data-lucide="printer" style="width:15px;height:15px;vertical-align:middle"></i> Cetak
        </button>
        <a href="<?= url('admin/pengaduan') ?>" class="btn btn-secondary no-print">← Kembali</a>
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

<div style="display:grid;grid-template-columns:2fr 1fr;gap:20px;align-items:start">

    <!-- Kolom Kiri -->
    <div style="display:flex;flex-direction:column;gap:20px">

        <!-- Info Pengaduan -->
        <div class="content-card">
            <div class="section-header">
                <h2><?= htmlspecialchars($pengaduan['judul']) ?></h2>
                <span class="status-badge <?= $statusClass ?>"><?= htmlspecialchars($pengaduan['status']) ?></span>
            </div>
            <div style="padding:20px">

                <!-- Progress -->
                <div style="margin-bottom:20px">
                    <div class="progress-label">
                        <span style="font-size:13px;font-weight:600;color:var(--gray-600)">Progress Perbaikan</span>
                        <span style="font-weight:700;color:var(--primary)"><?= (int)$pengaduan['progress'] ?>%</span>
                    </div>
                    <div class="progress" style="height:10px">
                        <div class="progress-bar" style="width:<?= (int)$pengaduan['progress'] ?>%"></div>
                    </div>
                </div>

                <!-- Info Grid -->
                <div class="info-grid" style="margin-bottom:20px">
                    <div class="info-item">
                        <label>Pelapor</label>
                        <span><?= htmlspecialchars($pengaduan['nama_siswa'] ?? '-') ?></span>
                        <?php if ($nisPelapor && $nisPelapor !== '-'): ?>
                        <span style="font-size:12px;color:var(--gray-400);margin-top:2px;display:block">NIS: <?= htmlspecialchars($nisPelapor) ?></span>
                        <?php endif; ?>
                    </div>
                    <div class="info-item">
                        <label>Kategori</label>
                        <span><?= htmlspecialchars($pengaduan['nama_kategori'] ?? '-') ?></span>
                    </div>
                    <div class="info-item">
                        <label>Lokasi</label>
                        <span><?= htmlspecialchars($pengaduan['lokasi']) ?></span>
                    </div>
                    <div class="info-item">
                        <label>Tanggal</label>
                        <span><?= date('d M Y, H:i', strtotime($pengaduan['created_at'])) ?></span>
                    </div>
                </div>

                <!-- Deskripsi -->
                <div style="margin-bottom:20px">
                    <p style="font-size:11px;font-weight:600;text-transform:uppercase;letter-spacing:0.5px;color:var(--gray-400);margin-bottom:8px">Deskripsi</p>
                    <p style="font-size:13.5px;color:var(--gray-700);line-height:1.7;background:var(--gray-50);padding:14px;border-radius:var(--radius-sm)">
                        <?= nl2br(htmlspecialchars($pengaduan['deskripsi'])) ?>
                    </p>
                </div>

                <!-- Foto -->
                <?php if (!empty($pengaduan['foto'])): ?>
                <div>
                    <p style="font-size:11px;font-weight:600;text-transform:uppercase;letter-spacing:0.5px;color:var(--gray-400);margin-bottom:8px">Foto Kerusakan</p>
                    <img src="<?= url('uploads/pengaduan/' . htmlspecialchars($pengaduan['foto'])) ?>"
                             alt="Foto kerusakan"
                             onclick="openLightbox(this.src)"
                             style="width:100%;max-width:320px;height:200px;object-fit:cover;border-radius:var(--radius);border:1px solid var(--gray-200);cursor:zoom-in;display:block">
                    <p style="font-size:11px;color:var(--gray-400);margin-top:6px">Klik foto untuk memperbesar</p>
                </div>
                <?php endif; ?>

            </div>
        </div>

    </div>

    <!-- Kolom Kanan -->
    <div style="display:flex;flex-direction:column;gap:20px">

        <!-- Update Status + Umpan Balik (1 form) -->
        <div class="content-card">
            <div class="section-header"><h2>Update & Umpan Balik</h2></div>
            <?php 
            // Cek apakah status sudah final (Selesai atau Ditolak)
            $isFinalStatus = in_array($pengaduan['status'], ['Selesai', 'Ditolak']);
            ?>
            <?php if ($isFinalStatus): ?>
                <div style="padding:20px">
                    <div style="display:flex;align-items:center;gap:10px;padding:14px;background:var(--gray-100);border-radius:var(--radius);margin-bottom:16px">
                        <i data-lucide="lock" style="width:18px;height:18px;color:var(--gray-400)"></i>
                        <span style="font-size:13px;color:var(--gray-500)">Status pengaduan sudah final dan tidak dapat diubah.</span>
                    </div>
                    <div class="form-group">
                        <label>Status Saat Ini</label>
                        <input type="text" value="<?= htmlspecialchars($pengaduan['status']) ?>" disabled style="background:var(--gray-100)">
                    </div>
                    <div class="form-group">
                        <label>Progress</label>
                        <input type="text" value="<?= (int)$pengaduan['progress'] ?>%" disabled style="background:var(--gray-100)">
                    </div>
                </div>
            <?php else: ?>
                <form action="<?= url('admin/pengaduan/update/' . $pengaduan['id']) ?>" method="POST" style="padding:20px">
                    <div class="form-group">
                        <label>Status</label>
                        <select name="status" required onchange="autoProgress(this)">
                            <?php foreach (['Diajukan','Diproses','Selesai','Ditolak'] as $s): ?>
                                <option value="<?= $s ?>" <?= $pengaduan['status'] === $s ? 'selected' : '' ?>>
                                    <?= $s ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <input type="hidden" name="progress" id="auto-progress" value="<?= (int)$pengaduan['progress'] ?>">
                    <div class="form-group">
                        <label>Keterangan Histori</label>
                        <textarea name="keterangan" rows="3" placeholder="Tambahkan keterangan perubahan status..."></textarea>
                    </div>
                    <div class="form-group">
                        <label>Umpan Balik ke Siswa <span style="color:var(--gray-400);font-weight:400">(opsional)</span></label>
                        <textarea name="umpan_balik" rows="3" placeholder="Tulis pesan untuk siswa..."></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary btn-full">Simpan Semua</button>
                </form>
            <?php endif; ?>
        </div>

        <script>
        const progressMap = { 'Diajukan': 0, 'Diproses': 50, 'Selesai': 100, 'Ditolak': 0 };
        function autoProgress(sel) {
            document.getElementById('auto-progress').value = progressMap[sel.value] ?? 0;
        }
        </script>

        <!-- Timeline -->
        <div class="content-card">
            <div class="section-header"><h2>Riwayat Status</h2></div>
            <div style="padding:16px 22px">
                <?php if (!empty($histori)): ?>
                <div class="timeline">
                    <?php foreach ($histori as $item):
                        $dotColor = match($item['status'] ?? '') {
                            'Selesai'  => 'var(--success)',
                            'Ditolak'  => 'var(--danger)',
                            'Diproses' => 'var(--info)',
                            default    => 'var(--primary)'
                        };
                    ?>
                    <div class="timeline-item">
                        <div class="timeline-dot" style="background:<?= $dotColor ?>"></div>
                        <div class="timeline-content">
                            <div class="timeline-title"><?= htmlspecialchars($item['status']) ?> — <?= (int)$item['progress'] ?>%</div>
                            <?php if (!empty($item['keterangan'])): ?>
                            <div class="timeline-desc"><?= nl2br(htmlspecialchars($item['keterangan'])) ?></div>
                            <?php endif; ?>
                            <div class="timeline-time"><?= date('d M Y, H:i', strtotime($item['created_at'])) ?></div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
                <?php else: ?>
                <p style="font-size:13px;color:var(--gray-400)">Belum ada riwayat.</p>
                <?php endif; ?>
            </div>
        </div>

    </div>

</div>

<style>
@media print {
    .no-print, .sidebar, .mobile-topbar, .sidebar-overlay { display: none !important; }
    .main-content { margin-left: 0 !important; }
    .app-wrapper { display: block !important; }
    body { background: white !important; }
    .content-card { border: 1px solid #ddd !important; box-shadow: none !important; }
    .page-body { padding: 0 !important; }
    .footer { display: none !important; }
    div[style*="grid-template-columns"] { display: block !important; }
    img { max-width: 100% !important; height: auto !important; max-height: 280px !important; }
    .status-badge { border: 1px solid #ccc !important; }
    .progress-bar { -webkit-print-color-adjust: exact; print-color-adjust: exact; }
}
</style>

<?php require_once __DIR__ . '/../../layouts/footer.php'; ?>
