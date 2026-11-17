<?php
$pageTitle = 'Detail Pengaduan - SIADU';
require_once __DIR__ . '/../../layouts/header.php';
require_once __DIR__ . '/../../layouts/sidebar.php';

$statusClass = match($pengaduan['status'] ?? '') {
    'Diajukan'        => 'badge-diajukan',
    'Diproses'        => 'badge-diproses',
        'Selesai'         => 'badge-selesai',
    'Ditolak'         => 'badge-ditolak',
    default           => ''
};
?>

<div class="page-header">
    <div>
        <h1>Detail Pengaduan</h1>
        <p style="color:var(--gray-400);font-size:13px">#<?= $pengaduan['id'] ?></p>
    </div>
    <a href="<?= url('siswa/pengaduan') ?>" class="btn btn-secondary">← Kembali</a>
</div>

<div style="display:grid;grid-template-columns:2fr 1fr;gap:20px;align-items:start">

    <!-- Kolom Kiri -->
    <div style="display:flex;flex-direction:column;gap:20px">

        <!-- Info Utama -->
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
                        <label>Kategori</label>
                        <span><?= htmlspecialchars($pengaduan['nama_kategori'] ?? '-') ?></span>
                    </div>
                    <div class="info-item">
                        <label>Lokasi</label>
                        <span><?= htmlspecialchars($pengaduan['lokasi']) ?></span>
                    </div>
                    <div class="info-item">
                        <label>Tanggal Dibuat</label>
                        <span><?= date('d M Y, H:i', strtotime($pengaduan['created_at'])) ?></span>
                    </div>
                    <div class="info-item">
                        <label>Pelapor</label>
                        <span><?= htmlspecialchars($pengaduan['nama_siswa'] ?? $_SESSION['user']['nama']) ?></span>
                    </div>
                </div>

                <!-- Deskripsi -->
                <div>
                    <p style="font-size:11px;font-weight:600;text-transform:uppercase;letter-spacing:0.5px;color:var(--gray-400);margin-bottom:8px">Deskripsi</p>
                    <p style="font-size:14px;color:var(--gray-700);line-height:1.7;background:var(--gray-50);padding:14px;border-radius:var(--radius-sm)">
                        <?= nl2br(htmlspecialchars($pengaduan['deskripsi'])) ?>
                    </p>
                </div>

                <!-- Foto -->
                <?php if (!empty($pengaduan['foto'])): ?>
                <div style="margin-top:20px">
                    <p style="font-size:11px;font-weight:600;text-transform:uppercase;letter-spacing:0.5px;color:var(--gray-400);margin-bottom:8px">Foto Kerusakan</p>
                        <img src="<?= url('uploads/pengaduan/' . htmlspecialchars($pengaduan['foto'])) ?>"
                             alt="Foto kerusakan"
                             onclick="openLightbox(this.src)"
                             style="width:100%;max-width:320px;height:200px;object-fit:cover;border-radius:var(--radius);border:1px solid var(--gray-200);cursor:zoom-in;display:block">
                    <p style="font-size:11px;color:var(--gray-400);margin-top:6px">Klik foto untuk memperbesar</p>                </div>
                <?php endif; ?>

            </div>
        </div>

        <!-- Feedback Admin -->
        <div class="content-card">
            <div class="section-header"><h2>Umpan Balik Admin</h2></div>
            <?php
            $feedbackModel = new Feedback();
            $feedbackList  = $feedbackModel->getByPengaduan($pengaduan['id']);
            ?>
            <?php if (!empty($feedbackList)): ?>
            <div style="padding:8px 0">
                <?php foreach ($feedbackList as $fb): ?>
                <div style="padding:16px 22px;border-bottom:1px solid var(--gray-100)">
                    <div style="display:flex;justify-content:space-between;margin-bottom:6px">
                        <span style="font-weight:600;font-size:13.5px;color:var(--gray-800)"><?= htmlspecialchars($fb['nama_admin']) ?></span>
                        <span style="font-size:12px;color:var(--gray-400)"><?= date('d M Y, H:i', strtotime($fb['created_at'])) ?></span>
                    </div>
                    <p style="font-size:13.5px;color:var(--gray-600);line-height:1.6"><?= nl2br(htmlspecialchars($fb['isi'])) ?></p>
                </div>
                <?php endforeach; ?>
            </div>
            <?php else: ?>
            <div class="empty-state">
                <div class="empty-state-icon" style="font-size:28px"><i data-lucide="message-circle" style="width:40px;height:40px;stroke-width:1.5"></i></div>
                <p>Belum ada umpan balik dari admin.</p>
            </div>
            <?php endif; ?>
        </div>

    </div>

    <!-- Kolom Kanan: Timeline -->
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

<?php require_once __DIR__ . '/../../layouts/footer.php'; ?>
