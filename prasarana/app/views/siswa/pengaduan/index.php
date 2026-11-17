<?php
$pageTitle = 'Pengaduan Saya - SIADU';
require_once __DIR__ . '/../../layouts/header.php';
require_once __DIR__ . '/../../layouts/sidebar.php';
?>

<div class="page-header">
    <div>
        <h1>Pengaduan Saya</h1>
        <p>Lihat seluruh pengaduan yang pernah kamu kirim.</p>
    </div>
    <a href="<?= url('siswa/pengaduan/create') ?>" class="btn btn-primary">+ Buat Pengaduan</a>
</div>

<section class="content-card">
    <?php if (!empty($pengaduan)): ?>

    <!-- Desktop -->
    <div class="table-wrapper desktop-only">
        <table>
            <thead>
                <tr><th>ID</th><th>Judul</th><th>Kategori</th><th>Lokasi</th><th>Status</th><th>Progress</th><th>Aksi</th></tr>
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
                    <td style="color:var(--gray-400);font-weight:600">#<?= $item['id'] ?></td>
                    <td style="font-weight:500"><?= htmlspecialchars($item['judul']) ?></td>
                    <td><?= htmlspecialchars($item['nama_kategori']) ?></td>
                    <td><?= htmlspecialchars($item['lokasi']) ?></td>
                    <td><span class="status-badge <?= $statusClass ?>"><?= htmlspecialchars($item['status']) ?></span></td>
                    <td>
                        <div class="progress" style="min-width:80px">
                            <div class="progress-bar" style="width:<?= (int)$item['progress'] ?>%"></div>
                        </div>
                        <small><?= (int)$item['progress'] ?>%</small>
                    </td>
                    <td>
                        <div style="display:flex;gap:6px">
                            <a href="<?= url('siswa/pengaduan/detail/' . $item['id']) ?>" class="btn btn-sm btn-outline">Detail</a>
                            <?php if ($item['status'] === 'Diajukan'): ?>
                            <a href="<?= url('siswa/pengaduan/edit/' . $item['id']) ?>" class="btn btn-sm btn-secondary">Edit</a>
                            <a href="<?= url('siswa/pengaduan/delete/' . $item['id']) ?>" class="btn btn-sm btn-danger" onclick="return confirm('Yakin hapus?')">Hapus</a>
                            <?php endif; ?>
                        </div>
                    </td>
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
                </div>
                <span class="status-badge <?= $statusClass ?>" style="flex-shrink:0"><?= htmlspecialchars($item['status']) ?></span>
            </div>
            <div class="progress" style="height:5px;margin-bottom:10px">
                <div class="progress-bar" style="width:<?= (int)$item['progress'] ?>%"></div>
            </div>
            <div style="display:flex;gap:6px">
                <a href="<?= url('siswa/pengaduan/detail/' . $item['id']) ?>" class="btn btn-sm btn-outline" style="flex:1;justify-content:center">Detail</a>
                <?php if ($item['status'] === 'Diajukan'): ?>
                <a href="<?= url('siswa/pengaduan/edit/' . $item['id']) ?>" class="btn btn-sm btn-secondary" style="flex:1;justify-content:center">Edit</a>
                <a href="<?= url('siswa/pengaduan/delete/' . $item['id']) ?>" class="btn btn-sm btn-danger" onclick="return confirm('Yakin hapus?')" style="flex:1;justify-content:center">Hapus</a>
                <?php endif; ?>
            </div>
        </div>
        <?php endforeach; ?>
    </div>

    <?php else: ?>
    <div class="empty-state">
        <div class="empty-state-icon"><i data-lucide="inbox" style="width:40px;height:40px;stroke-width:1.5"></i></div>
        <h3>Belum ada pengaduan</h3>
        <p>Kamu belum mengirim pengaduan apa pun.</p>
        <a href="<?= url('siswa/pengaduan/create') ?>" class="btn btn-primary">Buat Pengaduan</a>
    </div>
    <?php endif; ?>
</section>

<?php require_once __DIR__ . '/../../layouts/footer.php'; ?>
