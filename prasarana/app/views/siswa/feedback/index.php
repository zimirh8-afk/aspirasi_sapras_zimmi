<?php
$pageTitle = 'Umpan Balik - SIADU';
require_once __DIR__ . '/../../layouts/header.php';
require_once __DIR__ . '/../../layouts/sidebar.php';
?>

<div class="page-header">
    <div>
        <h1>Umpan Balik</h1>
        <p>Tanggapan admin terhadap pengaduanmu.</p>
    </div>
</div>

<section class="content-card">
    <?php if (!empty($feedback)): ?>
    <div style="padding:4px 0">
        <?php foreach ($feedback as $item):
            $statusClass = match($item['status'] ?? '') {
                'Diajukan'        => 'badge-diajukan',
                'Diproses'        => 'badge-diproses',
                                'Selesai'         => 'badge-selesai',
                'Ditolak'         => 'badge-ditolak',
                default           => ''
            };
        ?>
        <div style="padding:16px 20px;border-bottom:1px solid var(--gray-100)">
            <div style="display:flex;align-items:flex-start;justify-content:space-between;gap:10px;margin-bottom:8px">
                <div>
                    <div style="font-weight:600;font-size:14px"><?= htmlspecialchars($item['judul_pengaduan'] ?? '-') ?></div>
                    <div style="font-size:12px;color:var(--gray-500);margin-top:2px">Admin: <?= htmlspecialchars($item['nama_admin'] ?? '-') ?></div>
                </div>
                <div style="display:flex;flex-direction:column;align-items:flex-end;gap:4px;flex-shrink:0">
                    <span class="status-badge <?= $statusClass ?>"><?= htmlspecialchars($item['status'] ?? '-') ?></span>
                    <span style="font-size:11px;color:var(--gray-400)"><?= (int)$item['progress'] ?>%</span>
                </div>
            </div>
            <div style="background:var(--gray-50);padding:12px;border-radius:var(--radius-sm);font-size:13.5px;color:var(--gray-700);line-height:1.6">
                <?= nl2br(htmlspecialchars($item['isi'])) ?>
            </div>
            <div style="font-size:11px;color:var(--gray-400);margin-top:8px"><?= date('d M Y, H:i', strtotime($item['created_at'])) ?></div>
        </div>
        <?php endforeach; ?>
    </div>
    <?php else: ?>
    <div class="empty-state">
        <h3>Belum ada umpan balik</h3>
        <p>Admin belum memberikan tanggapan.</p>
    </div>
    <?php endif; ?>
</section>

<?php require_once __DIR__ . '/../../layouts/footer.php'; ?>
