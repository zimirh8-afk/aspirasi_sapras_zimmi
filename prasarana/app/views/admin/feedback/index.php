<?php
$pageTitle = 'Umpan Balik - Admin';
require_once __DIR__ . '/../../layouts/header.php';
require_once __DIR__ . '/../../layouts/sidebar.php';
?>

<div class="page-header">
    <div><h1>Umpan Balik</h1></div>
</div>

<div class="content-card">

    <?php if (!empty($feedback)): ?>

    <!-- Desktop -->
    <div class="table-wrapper desktop-only">
        <table>
            <thead>
                <tr>
                    <th>Pengaduan</th><th>Siswa</th><th>Admin</th><th>Umpan Balik</th><th>Tanggal</th>
                </tr>
            </thead>
            <tbody>
            <?php foreach ($feedback as $item): ?>
                <tr>
                    <td><?= htmlspecialchars($item['judul_pengaduan'] ?? '-') ?></td>
                    <td><?= htmlspecialchars($item['nama_siswa'] ?? '-') ?></td>
                    <td><?= htmlspecialchars($item['nama_admin'] ?? '-') ?></td>
                    <td><?= nl2br(htmlspecialchars($item['isi'])) ?></td>
                    <td style="white-space:nowrap"><?= date('d M Y H:i', strtotime($item['created_at'])) ?></td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <!-- Mobile -->
    <div class="mobile-only">
        <?php foreach ($feedback as $item): ?>
        <div style="padding:14px 16px;border-bottom:1px solid var(--gray-100)">
            <div style="font-weight:600;font-size:13.5px;margin-bottom:4px">
                <?= htmlspecialchars($item['judul_pengaduan'] ?? '-') ?>
            </div>
            <div style="font-size:12px;color:var(--gray-500);margin-bottom:8px">
                <?= htmlspecialchars($item['nama_siswa'] ?? '-') ?> &middot; Admin: <?= htmlspecialchars($item['nama_admin'] ?? '-') ?>
            </div>
            <div style="font-size:13px;color:var(--gray-700);background:var(--gray-50);padding:10px;border-radius:var(--radius-sm);line-height:1.6">
                <?= nl2br(htmlspecialchars($item['isi'])) ?>
            </div>
            <div style="font-size:11px;color:var(--gray-400);margin-top:6px">
                <?= date('d M Y, H:i', strtotime($item['created_at'])) ?>
            </div>
        </div>
        <?php endforeach; ?>
    </div>

    <?php else: ?>
    <div class="empty-state">
        <h3>Belum ada umpan balik</h3>
    </div>
    <?php endif; ?>

</div>

<?php require_once __DIR__ . '/../../layouts/footer.php'; ?>
