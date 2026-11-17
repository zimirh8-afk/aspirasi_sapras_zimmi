<?php
$pageTitle = 'Histori Pengaduan - Admin';
require_once __DIR__ . '/../../layouts/header.php';
require_once __DIR__ . '/../../layouts/sidebar.php';
?>

<div class="page-header">
    <div>
        <h1>Histori Pengaduan</h1>
        <p>Riwayat seluruh pengaduan sarana prasarana.</p>
    </div>
    <?php if (!empty($feedback)): ?>
    <button onclick="cetakHistori()" class="btn btn-secondary no-print">
        <i data-lucide="printer" style="width:15px;height:15px;vertical-align:middle"></i> Cetak
    </button>
    <?php endif; ?>
</div>

<div class="content-card" id="print-area">

    <!-- Header cetak (hanya muncul saat print) -->
    <div class="print-header" style="display:none">
        <div style="text-align:center;padding:20px 0 10px">
            <img src="<?= url('logo.png') ?>" alt="SIADU" style="height:60px;margin-bottom:8px">
            <h2 style="font-size:16px;margin:0">Laporan Histori Pengaduan Sarana Prasarana</h2>
            <p style="font-size:12px;color:#666;margin:4px 0">Dicetak pada: <?= date('d M Y, H:i') ?></p>
            <hr style="margin:12px 0">
        </div>
    </div>

    <?php if (!empty($feedback)): ?>

    <!-- Desktop + Print table -->
    <div class="table-wrapper desktop-only">
        <table id="print-table">
            <thead>
                <tr>
                    <th>No</th><th>Judul</th><th>Siswa</th><th>Kategori</th><th>Status</th><th>Progress</th><th>Tanggal</th><th class="no-print">Aksi</th>
                </tr>
            </thead>
            <tbody>
            <?php foreach ($feedback as $i => $item):
                $statusClass = match($item['status'] ?? '') {
                    'Diajukan' => 'badge-diajukan',
                    'Diproses' => 'badge-diproses',
                    'Selesai'  => 'badge-selesai',
                    'Ditolak'  => 'badge-ditolak',
                    default    => ''
                };
            ?>
                <tr>
                    <td><?= $i + 1 ?></td>
                    <td style="font-weight:500"><?= htmlspecialchars($item['judul'] ?? '-') ?></td>
                    <td><?= htmlspecialchars($item['nama_siswa'] ?? '-') ?></td>
                    <td><?= htmlspecialchars($item['nama_kategori'] ?? '-') ?></td>
                    <td><span class="status-badge <?= $statusClass ?>"><?= htmlspecialchars($item['status'] ?? '-') ?></span></td>
                    <td><?= (int)($item['progress'] ?? 0) ?>%</td>
                    <td style="white-space:nowrap"><?= date('d M Y', strtotime($item['created_at'])) ?></td>
                    <td class="no-print"><a href="<?= url('admin/pengaduan/detail/' . $item['id']) ?>" class="btn btn-sm btn-outline">Detail</a></td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <!-- Mobile cards -->
    <div class="mobile-only">
        <?php foreach ($feedback as $item):
            $statusClass = match($item['status'] ?? '') {
                'Diajukan' => 'badge-diajukan',
                'Diproses' => 'badge-diproses',
                'Selesai'  => 'badge-selesai',
                'Ditolak'  => 'badge-ditolak',
                default    => ''
            };
        ?>
        <div style="padding:14px 16px;border-bottom:1px solid var(--gray-100);display:flex;align-items:center;gap:12px">
            <div style="flex:1;min-width:0">
                <div style="font-weight:600;font-size:13.5px;margin-bottom:3px"><?= htmlspecialchars($item['judul'] ?? '-') ?></div>
                <div style="font-size:12px;color:var(--gray-500)"><?= htmlspecialchars($item['nama_siswa'] ?? '-') ?> &middot; <?= htmlspecialchars($item['nama_kategori'] ?? '-') ?></div>
                <div style="margin-top:6px">
                    <div class="progress" style="height:5px">
                        <div class="progress-bar" style="width:<?= (int)($item['progress'] ?? 0) ?>%"></div>
                    </div>
                </div>
            </div>
            <div style="display:flex;flex-direction:column;align-items:flex-end;gap:6px;flex-shrink:0">
                <span class="status-badge <?= $statusClass ?>"><?= htmlspecialchars($item['status'] ?? '-') ?></span>
                <a href="<?= url('admin/pengaduan/detail/' . $item['id']) ?>" class="btn btn-sm btn-outline">Detail</a>
            </div>
        </div>
        <?php endforeach; ?>
    </div>

    <!-- Footer cetak -->
    <div class="print-footer" style="display:none;padding:10px 0;text-align:center;font-size:11px;color:#888;border-top:1px solid #ddd;margin-top:16px">
        SIADU &mdash; Sistem Pengaduan Sarana Prasarana Sekolah
    </div>

    <?php else: ?>
    <div class="empty-state">
        <div class="empty-state-icon"><i data-lucide="clock" style="width:40px;height:40px;stroke-width:1.5"></i></div>
        <h3>Belum ada histori</h3>
        <p>Belum ada pengaduan yang masuk.</p>
    </div>
    <?php endif; ?>

</div>

<style>
@media print {
    .no-print, .sidebar, .mobile-topbar, .page-header button,
    .sidebar-overlay, .mobile-only { display: none !important; }
    .desktop-only { display: block !important; }
    .print-header, .print-footer { display: block !important; }
    .main-content { margin-left: 0 !important; }
    .content-card { border: none !important; box-shadow: none !important; }
    body { background: white !important; }
    .status-badge { border: 1px solid #ccc !important; }
    th { background: #f0f0f0 !important; -webkit-print-color-adjust: exact; }
}
</style>

<script>
function cetakHistori() {
    window.print();
}
</script>

<?php require_once __DIR__ . '/../../layouts/footer.php'; ?>
