<?php
$pageTitle = 'Dashboard - SIADU';
require_once __DIR__ . '/../layouts/header.php';
require_once __DIR__ . '/../layouts/sidebar.php';
?>

<div class="welcome-banner">
    <div>
        <h2>Halo, <?= htmlspecialchars($_SESSION['user']['nama']) ?></h2>
        <p>Pantau dan kelola pengaduan sarana prasarana kamu di sini.</p>
    </div>
    <div class="welcome-actions">
        <a href="<?= url('siswa/pengaduan/create') ?>" class="btn">
            <i data-lucide="plus" style="width:16px;height:16px"></i> Buat Pengaduan
        </a>
    </div>
</div>

<div class="stats-grid">
    <div class="stat-card primary">
        <div class="stat-card-icon"><i data-lucide="clipboard-list"></i></div>
        <span>Total Pengaduan</span>
        <strong><?= $totalPengaduan ?? 0 ?></strong>
    </div>
    <div class="stat-card info">
        <div class="stat-card-icon"><i data-lucide="settings"></i></div>
        <span>Diproses</span>
        <strong><?= $diproses ?? 0 ?></strong>
    </div>
    <div class="stat-card success">
        <div class="stat-card-icon"><i data-lucide="check-circle"></i></div>
        <span>Selesai</span>
        <strong><?= $selesai ?? 0 ?></strong>
    </div>
</div>

<div class="content-card">

    <div class="section-header">
        <h2>Pengaduan Terbaru</h2>
        <a href="<?= url('siswa/pengaduan') ?>">Lihat Semua →</a>
    </div>

    <?php if (!empty($terbaru)): ?>
    <div class="complaint-list">
        <?php foreach ($terbaru as $item):
            $statusClass = match($item['status'] ?? '') {
                'Diajukan'        => 'badge-diajukan',
                'Diproses'        => 'badge-diproses',
                'Dalam Perbaikan' => 'badge-perbaikan',
                'Selesai'         => 'badge-selesai',
                'Ditolak'         => 'badge-ditolak',
                default           => ''
            };
        ?>
        <div class="complaint-card">
            <div class="complaint-card-body">
                <h3><?= htmlspecialchars($item['judul'] ?? '-') ?></h3>
                <p><?= htmlspecialchars($item['nama_kategori'] ?? '-') ?> &middot; <?= htmlspecialchars($item['lokasi'] ?? '-') ?></p>
            </div>
            <div class="complaint-card-meta">
                <span class="status-badge <?= $statusClass ?>">
                    <?= htmlspecialchars($item['status'] ?? '-') ?>
                </span>
                <div class="progress-wrapper" style="margin-top:8px;min-width:120px">
                    <div class="progress-label">
                        <span>Progress</span>
                        <span><?= (int)($item['progress'] ?? 0) ?>%</span>
                    </div>
                    <div class="progress">
                        <div class="progress-bar" style="width:<?= (int)($item['progress'] ?? 0) ?>%"></div>
                    </div>
                </div>
            </div>
            <a href="<?= url('siswa/pengaduan/detail/' . $item['id']) ?>" class="btn btn-sm btn-outline">
                Detail
            </a>
        </div>
        <?php endforeach; ?>
    </div>
    <?php else: ?>
    <div class="empty-state">
        <div class="empty-state-icon"><i data-lucide="inbox" style="width:40px;height:40px;stroke-width:1.5"></i></div>
        <h3>Belum ada pengaduan</h3>
        <p>Kamu belum memiliki pengaduan. Buat pengaduan pertamamu!</p>
        <a href="<?= url('siswa/pengaduan/create') ?>" class="btn btn-primary">
            <i data-lucide="plus" style="width:14px;height:14px;vertical-align:middle"></i> Buat Pengaduan
        </a>
    </div>
    <?php endif; ?>

</div>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
