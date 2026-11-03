<?php
$pageTitle = 'Verifikasi Akun - Admin';
require_once __DIR__ . '/../../layouts/header.php';
require_once __DIR__ . '/../../layouts/sidebar.php';
?>

<div class="page-header">
    <div>
        <h1>Verifikasi Akun Siswa</h1>
        <p>Kelola seluruh akun siswa yang mendaftar.</p>
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

<div class="content-card">

    <?php if (!empty($siswa)): ?>

    <!-- Desktop -->
    <div class="table-wrapper desktop-only">
        <table>
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama</th>
                    <th>Username</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
            <?php foreach ($siswa as $i => $item):
                $isPending = $item['status_akun'] === 'pending';
                $isAktif   = $item['status_akun'] === 'aktif';
            ?>
                <tr style="<?= !$isPending ? 'opacity:0.65' : '' ?>">
                    <td style="color:var(--gray-400)"><?= $i + 1 ?></td>
                    <td style="font-weight:600">
                        <div style="display:flex;align-items:center;gap:10px">
                            <div style="width:34px;height:34px;border-radius:50%;background:linear-gradient(135deg,<?= $isPending ? 'var(--primary),var(--purple)' : ($isAktif ? 'var(--success),#059669' : 'var(--gray-400),var(--gray-500)') ?>);display:flex;align-items:center;justify-content:center;color:white;font-weight:700;font-size:13px;flex-shrink:0">
                                <?= strtoupper(substr($item['nama'], 0, 1)) ?>
                            </div>
                            <?= htmlspecialchars($item['nama']) ?>
                        </div>
                    </td>
                    <td style="color:var(--gray-500)">@<?= htmlspecialchars($item['username']) ?></td>
                    <td>
                        <?php if ($isPending): ?>
                            <span class="status-badge badge-diajukan">Menunggu</span>
                        <?php elseif ($isAktif): ?>
                            <span class="status-badge badge-selesai">Disetujui</span>
                        <?php else: ?>
                            <span class="status-badge badge-ditolak">Ditolak</span>
                        <?php endif; ?>
                    </td>
                    <td>
                        <?php if ($isPending): ?>
                            <div style="display:flex;gap:8px">
                                <a href="<?= url('admin/verifikasi/approve/' . $item['id']) ?>" class="btn btn-success btn-sm" onclick="return confirm('Setujui akun <?= htmlspecialchars($item['nama']) ?>?')"><i data-lucide="check" style="width:14px;height:14px;vertical-align:middle"></i> Setujui</a>
                                <a href="<?= url('admin/verifikasi/tolak/' . $item['id']) ?>" class="btn btn-danger btn-sm" onclick="return confirm('Tolak akun <?= htmlspecialchars($item['nama']) ?>?')"><i data-lucide="x" style="width:14px;height:14px;vertical-align:middle"></i> Tolak</a>
                            </div>
                        <?php elseif ($isAktif): ?>
                            <span style="font-size:12.5px;color:var(--gray-400);font-style:italic">Sudah disetujui</span>
                        <?php else: ?>
                            <span style="font-size:12.5px;color:var(--gray-400);font-style:italic">Sudah ditolak</span>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <!-- Mobile: card list -->
    <div class="mobile-only">
        <?php foreach ($siswa as $i => $item):
            $isPending = $item['status_akun'] === 'pending';
            $isAktif   = $item['status_akun'] === 'aktif';
            $bgColor   = $isPending ? 'linear-gradient(135deg,var(--primary),var(--purple))' : ($isAktif ? 'linear-gradient(135deg,var(--success),#059669)' : 'linear-gradient(135deg,var(--gray-400),var(--gray-500))');
        ?>
        <div style="padding:14px 16px;border-bottom:1px solid var(--gray-100);<?= !$isPending ? 'opacity:0.65' : '' ?>">
            <div style="display:flex;align-items:center;gap:12px">
                <div style="width:40px;height:40px;border-radius:50%;background:<?= $bgColor ?>;display:flex;align-items:center;justify-content:center;color:white;font-weight:700;font-size:15px;flex-shrink:0">
                    <?= strtoupper(substr($item['nama'], 0, 1)) ?>
                </div>
                <div style="flex:1;min-width:0">
                    <div style="font-weight:600;font-size:14px"><?= htmlspecialchars($item['nama']) ?></div>
                    <div style="font-size:12px;color:var(--gray-400)">@<?= htmlspecialchars($item['username']) ?></div>
                </div>
                <div style="flex-shrink:0">
                    <?php if ($isPending): ?>
                        <span class="status-badge badge-diajukan">Menunggu</span>
                    <?php elseif ($isAktif): ?>
                        <span class="status-badge badge-selesai">Disetujui</span>
                    <?php else: ?>
                        <span class="status-badge badge-ditolak">Ditolak</span>
                    <?php endif; ?>
                </div>
            </div>
            <?php if ($isPending): ?>
            <div style="display:flex;gap:8px;margin-top:10px;padding-left:52px">
                <a href="<?= url('admin/verifikasi/approve/' . $item['id']) ?>" class="btn btn-success btn-sm" onclick="return confirm('Setujui akun ini?')" style="flex:1;justify-content:center"><i data-lucide="check" style="width:14px;height:14px;vertical-align:middle"></i> Setujui</a>
                <a href="<?= url('admin/verifikasi/tolak/' . $item['id']) ?>" class="btn btn-danger btn-sm" onclick="return confirm('Tolak akun ini?')" style="flex:1;justify-content:center"><i data-lucide="x" style="width:14px;height:14px;vertical-align:middle"></i> Tolak</a>
            </div>
            <?php endif; ?>
        </div>
        <?php endforeach; ?>
    </div>

    <?php else: ?>
    <div class="empty-state">
        <div class="empty-state-icon"><i data-lucide="users" style="width:40px;height:40px;stroke-width:1.5"></i></div>
        <h3>Belum ada siswa terdaftar</h3>
        <p>Akun siswa yang mendaftar akan muncul di sini.</p>
    </div>
    <?php endif; ?>

</div>

<?php require_once __DIR__ . '/../../layouts/footer.php'; ?>
