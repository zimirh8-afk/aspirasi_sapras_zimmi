<?php
$pageTitle = 'Histori Pengaduan - SIADU';
require_once __DIR__ . '/../../layouts/header.php';
require_once __DIR__ . '/../../layouts/sidebar.php';
?>

<div class="page-header">
    <div><h1>Histori Pengaduan</h1></div>
</div>

<section class="content-card">
    <?php if (!empty($pengaduan)): ?>

    <!-- Desktop -->
    <div class="table-wrapper desktop-only">
        <table>
            <thead>
                <tr><th>#</th><th>Judul</th><th>Kategori</th><th>Lokasi</th><th>Status</th><th>Progress</th><th>Tanggal</th><th>Aksi</th></tr>
            </thead>
            <tbody>
            <?php foreach ($pengaduan as $item):
                $statusClass = match($item['status'] ?? '') {
                    'Diajukan'        => 'badge-diajukan',
                    'Diproses'        => 'badge-diproses',
                                        'Selesai'         => 'badge-selesai',
                    'Ditolak'         => 'badge-ditolak',
                    default           => ''
                };
            ?>
                <tr>
                    <td><?= (int)$item['id'] ?></td>
                    <td><?= htmlspecialchars($item['judul']) ?></td>
                    <td><?= htmlspecialchars($item['nama_kategori']) ?></td>
                    <td><?= htmlspecialchars($item['lokasi']) ?></td>
                    <td><span class="status-badge <?= $statusClass ?>"><?= htmlspecialchars($item['status']) ?></span></td>
                    <td><?= (int)$item['progress'] ?>%</td>
                    <td><?= date('d M Y', strtotime($item['created_at'])) ?></td>
                    <td><a href="<?= url('siswa/pengaduan/detail/' . (int)$item['id']) ?>" class="btn btn-sm btn-outline">Detail</a></td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <!-- Mobile -->
    <div class="mobile-only">
        <?php foreach ($pengaduan as $item):
            $statusClass = match($item['status'] ?? '') {
                'Diajukan'        => 'badge-diajukan',
                'Diproses'        => 'badge-diproses',
                                'Selesai'         => 'badge-selesai',
                'Ditolak'         => 'badge-ditolak',
                default           => ''
            };
        ?>
        <div style="padding:14px 16px;border-bottom:1px solid var(--gray-100)">
            <div style="display:flex;align-items:flex-start;justify-content:space-between;gap:10px;margin-bottom:8px">
                <div>
                    <div style="font-weight:600;font-size:14px"><?= htmlspecialchars($item['judul']) ?></div>
                    <div style="font-size:12px;color:var(--gray-500);margin-top:2px"><?= htmlspecialchars($item['nama_kategori']) ?> &middot; <?= htmlspecialchars($item['lokasi']) ?></div>
                    <div style="font-size:11px;color:var(--gray-400);margin-top:2px"><?= date('d M Y', strtotime($item['created_at'])) ?></div>
                </div>
                <div style="display:flex;flex-direction:column;align-items:flex-end;gap:6px;flex-shrink:0">
                    <span class="status-badge <?= $statusClass ?>"><?= htmlspecialchars($item['status']) ?></span>
                    <a href="<?= url('siswa/pengaduan/detail/' . (int)$item['id']) ?>" class="btn btn-sm btn-outline">Detail</a>
                </div>
            </div>
            <div class="progress" style="height:5px">
                <div class="progress-bar" style="width:<?= (int)$item['progress'] ?>%"></div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>

    <?php else: ?>
    <div class="empty-state">
        <p>Belum ada histori pengaduan.</p>
    </div>
    <?php endif; ?>
</section>

<?php require_once __DIR__ . '/../../layouts/footer.php'; ?>
