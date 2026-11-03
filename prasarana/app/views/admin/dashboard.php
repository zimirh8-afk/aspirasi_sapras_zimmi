<?php
$pageTitle = 'Dashboard Admin - SIADU';
require_once __DIR__ . '/../layouts/header.php';
require_once __DIR__ . '/../layouts/sidebar.php';
?>

<div class="page-header">
    <div>
        <h1>Dashboard</h1>
        <p>Selamat datang, <?= htmlspecialchars($_SESSION['user']['nama']) ?></p>
    </div>
</div>

<div class="stats-grid">
    <div class="stat-card primary">
        <div class="stat-card-icon"><i data-lucide="clipboard-list"></i></div>
        <span>Total Pengaduan</span>
        <strong><?= $totalPengaduan ?? 0 ?></strong>
    </div>
    <div class="stat-card warning">
        <div class="stat-card-icon"><i data-lucide="clock"></i></div>
        <span>Diajukan</span>
        <strong><?= $menunggu ?? 0 ?></strong>
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
    <?php if (($pendingAkun ?? 0) > 0): ?>
    <div class="stat-card danger" style="cursor:pointer" onclick="window.location='<?= url('admin/verifikasi') ?>'">
        <div class="stat-card-icon"><i data-lucide="user-check"></i></div>
        <span>Akun Menunggu</span>
        <strong><?= $pendingAkun ?></strong>
        <p style="font-size:11px;color:var(--danger);margin-top:4px;font-weight:600">Klik untuk verifikasi →</p>
    </div>
    <?php endif; ?>
</div>

<div style="display:grid;grid-template-columns:2fr 1fr;gap:20px;margin-bottom:20px">
    <!-- Statistik Bulanan -->
    <div class="content-card">
        <div class="section-header">
            <h2>Statistik Pengaduan Bulanan</h2>
        </div>
        <div style="padding:20px">
            <?php if (!empty($monthlyStats)): ?>
            <div style="height:280px;position:relative">
                <canvas id="monthlyChart"></canvas>
            </div>
            <?php else: ?>
            <div class="empty-state" style="padding:30px 0">
                <div class="empty-state-icon"><i data-lucide="bar-chart-2" style="width:32px;height:32px;stroke-width:1.5"></i></div>
                <p style="font-size:13px;color:var(--gray-400)">Belum ada data statistik</p>
            </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Ringkasan Status -->
    <div class="content-card">
        <div class="section-header">
            <h2>Ringkasan Status</h2>
        </div>
        <div style="padding:20px">
            <div style="display:flex;flex-direction:column;gap:16px">
                <?php 
                $totalAll = $totalPengaduan ?? 0;
                $percentages = [
                    'Diajukan' => $totalAll > 0 ? round((($menunggu ?? 0) / $totalAll) * 100) : 0,
                    'Diproses' => $totalAll > 0 ? round((($diproses ?? 0) / $totalAll) * 100) : 0,
                    'Selesai' => $totalAll > 0 ? round((($selesai ?? 0) / $totalAll) * 100) : 0,
                ];
                ?>
                <div>
                    <div style="display:flex;justify-content:space-between;margin-bottom:6px">
                        <span style="font-size:13px;font-weight:600;color:var(--gray-700)">Diajukan</span>
                        <span style="font-size:13px;color:var(--warning)"><?= $menunggu ?? 0 ?> (<?= $percentages['Diajukan'] ?>%)</span>
                    </div>
                    <div class="progress" style="height:8px;background:var(--gray-100)">
                        <div class="progress-bar" style="width:<?= $percentages['Diajukan'] ?>%;background:var(--warning)"></div>
                    </div>
                </div>
                <div>
                    <div style="display:flex;justify-content:space-between;margin-bottom:6px">
                        <span style="font-size:13px;font-weight:600;color:var(--gray-700)">Diproses</span>
                        <span style="font-size:13px;color:var(--info)"><?= $diproses ?? 0 ?> (<?= $percentages['Diproses'] ?>%)</span>
                    </div>
                    <div class="progress" style="height:8px;background:var(--gray-100)">
                        <div class="progress-bar" style="width:<?= $percentages['Diproses'] ?>%;background:var(--info)"></div>
                    </div>
                </div>
                <div>
                    <div style="display:flex;justify-content:space-between;margin-bottom:6px">
                        <span style="font-size:13px;font-weight:600;color:var(--gray-700)">Selesai</span>
                        <span style="font-size:13px;color:var(--success)"><?= $selesai ?? 0 ?> (<?= $percentages['Selesai'] ?>%)</span>
                    </div>
                    <div class="progress" style="height:8px;background:var(--gray-100)">
                        <div class="progress-bar" style="width:<?= $percentages['Selesai'] ?>%;background:var(--success)"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="content-card">

    <div class="section-header">
        <h2>Pengaduan Terbaru</h2>
        <a href="<?= url('admin/pengaduan') ?>">Lihat Semua →</a>
    </div>

    <?php if (!empty($terbaru)): ?>

    <!-- Desktop: tabel -->
    <div class="table-wrapper desktop-only">
        <table>
            <thead>
                <tr>
                    <th>Siswa</th>
                    <th>Judul Pengaduan</th>
                    <th>Kategori</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
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
                <tr>
                    <td>
                        <div style="display:flex;align-items:center;gap:10px">
                            <div style="width:36px;height:36px;border-radius:50%;background:linear-gradient(135deg,var(--primary),var(--purple));display:flex;align-items:center;justify-content:center;color:white;font-weight:700;font-size:13px;flex-shrink:0;overflow:hidden">
                                <?php if (!empty($item['foto_siswa'])): ?>
                                    <img src="<?= url('public/uploads/profil/' . htmlspecialchars($item['foto_siswa'])) ?>" style="width:100%;height:100%;object-fit:cover">
                                <?php else: ?>
                                    <?= strtoupper(substr($item['nama_siswa'] ?? 'S', 0, 1)) ?>
                                <?php endif; ?>
                            </div>
                            <div>
                                <div style="font-weight:600;font-size:13px"><?= htmlspecialchars($item['nama_siswa'] ?? '-') ?></div>
                                <?php if (!empty($item['nis_siswa'])): ?>
                                    <div style="font-size:11px;color:var(--gray-400)">NIS: <?= htmlspecialchars($item['nis_siswa']) ?></div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </td>
                    <td>
                        <div style="font-weight:500"><?= htmlspecialchars($item['judul'] ?? '-') ?></div>
                        <div style="font-size:11px;color:var(--gray-400);margin-top:2px"><?= date('d M Y, H:i', strtotime($item['created_at'])) ?></div>
                    </td>
                    <td><?= htmlspecialchars($item['nama_kategori'] ?? '-') ?></td>
                    <td><span class="status-badge <?= $statusClass ?>"><?= htmlspecialchars($item['status'] ?? '-') ?></span></td>
                    <td><a href="<?= url('admin/pengaduan/detail/' . $item['id']) ?>" class="btn btn-sm btn-outline">Detail</a></td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <!-- Mobile: card list -->
    <div class="mobile-only">
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
        <div style="padding:16px;border-bottom:1px solid var(--gray-100)">
            <div style="display:flex;align-items:center;gap:12px;margin-bottom:10px">
                <div style="width:40px;height:40px;border-radius:50%;background:linear-gradient(135deg,var(--primary),var(--purple));display:flex;align-items:center;justify-content:center;color:white;font-weight:700;font-size:14px;flex-shrink:0;overflow:hidden">
                    <?php if (!empty($item['foto_siswa'])): ?>
                        <img src="<?= url('public/uploads/profil/' . htmlspecialchars($item['foto_siswa'])) ?>" style="width:100%;height:100%;object-fit:cover">
                    <?php else: ?>
                        <?= strtoupper(substr($item['nama_siswa'] ?? 'S', 0, 1)) ?>
                    <?php endif; ?>
                </div>
                <div style="flex:1">
                    <div style="font-weight:600;font-size:14px"><?= htmlspecialchars($item['nama_siswa'] ?? '-') ?></div>
                    <?php if (!empty($item['nis_siswa'])): ?>
                        <div style="font-size:12px;color:var(--gray-400)">NIS: <?= htmlspecialchars($item['nis_siswa']) ?></div>
                    <?php endif; ?>
                </div>
                <span class="status-badge <?= $statusClass ?>"><?= htmlspecialchars($item['status'] ?? '-') ?></span>
            </div>
            <div style="font-size:13px;font-weight:500;margin-bottom:4px"><?= htmlspecialchars($item['judul'] ?? '-') ?></div>
            <div style="display:flex;justify-content:space-between;align-items:center">
                <span style="font-size:12px;color:var(--gray-400)"><?= htmlspecialchars($item['nama_kategori'] ?? '-') ?></span>
                <a href="<?= url('admin/pengaduan/detail/' . $item['id']) ?>" class="btn btn-sm btn-outline">Detail</a>
            </div>
        </div>
        <?php endforeach; ?>
    </div>

    <?php else: ?>
    <div class="empty-state">
        <div class="empty-state-icon"><i data-lucide="inbox" style="width:40px;height:40px;stroke-width:1.5"></i></div>
        <h3>Belum ada pengaduan</h3>
        <p>Pengaduan dari siswa akan muncul di sini.</p>
    </div>
    <?php endif; ?>

</div>

<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
<?php if (!empty($monthlyStats)): ?>
const ctx = document.getElementById('monthlyChart').getContext('2d');
const monthlyData = <?= json_encode($monthlyStats) ?>;

new Chart(ctx, {
    type: 'bar',
    data: {
        labels: monthlyData.map(d => d.bulan_label),
        datasets: [{
            label: 'Total Pengaduan',
            data: monthlyData.map(d => d.total),
            backgroundColor: 'rgba(99, 102, 241, 0.8)',
            borderColor: 'rgba(99, 102, 241, 1)',
            borderWidth: 1,
            borderRadius: 6
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                display: false
            },
            tooltip: {
                backgroundColor: 'rgba(0, 0, 0, 0.8)',
                padding: 12,
                titleFont: { size: 13, weight: 'bold' },
                bodyFont: { size: 12 }
            }
        },
        scales: {
            y: {
                beginAtZero: true,
                ticks: {
                    stepSize: 1,
                    font: { size: 11 }
                },
                grid: {
                    color: 'rgba(0, 0, 0, 0.05)'
                }
            },
            x: {
                ticks: {
                    font: { size: 10 }
                },
                grid: {
                    display: false
                }
            }
        }
    }
});
<?php endif; ?>
</script>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
