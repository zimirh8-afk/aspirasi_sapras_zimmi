<?php

$pageTitle = 'Data Pengaduan - Admin';

require_once __DIR__ . '/../../layouts/header.php';
require_once __DIR__ . '/../../layouts/sidebar.php';
?>

    <!-- Page Header -->
    <div class="page-header">

        <div>
            <h1>Data Pengaduan</h1>

            <p>
                Kelola dan pantau seluruh pengaduan sarana prasarana
                dari siswa.
            </p>
        </div>

    </div>


    <!-- Alert Success -->
    <?php if (!empty($_SESSION['success'])): ?>

        <div class="alert alert-success">

            <?= htmlspecialchars($_SESSION['success']) ?>

        </div>

        <?php unset($_SESSION['success']); ?>

    <?php endif; ?>


    <!-- Alert Error -->
    <?php if (!empty($_SESSION['error'])): ?>

        <div class="alert alert-error">

            <?= htmlspecialchars($_SESSION['error']) ?>

        </div>

        <?php unset($_SESSION['error']); ?>

    <?php endif; ?>


    <!-- Filter -->
    <section class="content-card" style="margin-bottom:20px">
        <form action="<?= url('admin/pengaduan') ?>" method="GET">
            <div style="display:flex;gap:10px;flex-wrap:wrap;padding:16px 20px;align-items:flex-end">

                <div style="flex:2;min-width:160px">
                    <label style="font-size:12px;font-weight:600;color:var(--gray-500);display:block;margin-bottom:5px">Cari</label>
                    <input type="text" name="search" value="<?= htmlspecialchars($filter['search'] ?? '') ?>" placeholder="Cari judul, siswa..." style="margin:0">
                </div>

                <div style="flex:1;min-width:130px">
                    <label style="font-size:12px;font-weight:600;color:var(--gray-500);display:block;margin-bottom:5px">Kategori</label>
                    <select name="kategori" style="margin:0">
                        <option value="">Semua Kategori</option>
                        <?php foreach ($kategori as $item): ?>
                            <option value="<?= $item['id'] ?>" <?= (($filter['kategori'] ?? '') == $item['id']) ? 'selected' : '' ?>>
                                <?= htmlspecialchars($item['nama']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div style="flex:1;min-width:130px">
                    <label style="font-size:12px;font-weight:600;color:var(--gray-500);display:block;margin-bottom:5px">Status</label>
                    <select name="status" style="margin:0">
                        <option value="">Semua Status</option>
                        <?php foreach (['Diajukan','Diproses','Selesai','Ditolak'] as $s): ?>
                            <option value="<?= $s ?>" <?= (($filter['status'] ?? '') === $s) ? 'selected' : '' ?>><?= $s ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div style="flex:1;min-width:130px">
                    <label style="font-size:12px;font-weight:600;color:var(--gray-500);display:block;margin-bottom:5px">Bulan</label>
                    <select name="bulan" style="margin:0">
                        <option value="">Semua Bulan</option>
                        <?php 
                        $bulanList = [
                            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
                            5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
                            9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
                        ];
                        foreach ($bulanList as $num => $nama): 
                        ?>
                            <option value="<?= $num ?>" <?= (($filter['bulan'] ?? '') == $num) ? 'selected' : '' ?>>
                                <?= $nama ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div style="flex:1;min-width:130px">
                    <label style="font-size:12px;font-weight:600;color:var(--gray-500);display:block;margin-bottom:5px">Tahun</label>
                    <select name="tahun" style="margin:0">
                        <option value="">Tahun Berjalan</option>
                        <?php 
                        $currentYear = (int) date('Y');
                        for ($y = $currentYear; $y >= $currentYear - 5; $y--): 
                        ?>
                            <option value="<?= $y ?>" <?= (($filter['tahun'] ?? '') == $y) ? 'selected' : '' ?>>
                                <?= $y ?>
                            </option>
                        <?php endfor; ?>
                    </select>
                </div>

                <div style="flex:1;min-width:130px">
                    <label style="font-size:12px;font-weight:600;color:var(--gray-500);display:block;margin-bottom:5px">Tanggal</label>
                    <input type="date" name="tanggal" value="<?= htmlspecialchars($filter['tanggal'] ?? '') ?>" style="margin:0">
                </div>

                <div style="display:flex;gap:8px;flex-shrink:0">
                    <button type="submit" class="btn btn-primary">Filter</button>
                    <a href="<?= url('admin/pengaduan') ?>" class="btn btn-secondary">Reset</a>
                </div>

            </div>
        </form>
    </section>


            <!-- Button -->
    <!-- Data Pengaduan -->
    <section class="content-card">

        <div class="section-header">

            <div>

                <h2>Daftar Pengaduan</h2>

                <p>
                    Seluruh pengaduan yang masuk dari siswa.
                </p>

            </div>

            <div>

                <strong>
                    Total:
                    <?= $totalPengaduan ?? count($pengaduan ?? []) ?>
                </strong>

            </div>

        </div>


        <?php if (!empty($pengaduan)): ?>

            <style>
                #tabel-pengaduan tbody tr:nth-child(odd) {
                    background-color: #f8fafc;
                }
                #tabel-pengaduan tbody tr:nth-child(even) {
                    background-color: #ffffff;
                }
                #tabel-pengaduan tbody tr:hover {
                    background-color: #f0f4ff !important;
                }
                #tabel-pengaduan thead th {
                    background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%);
                    color: white;
                    font-weight: 600;
                    text-transform: uppercase;
                    font-size: 11px;
                    letter-spacing: 0.5px;
                    padding: 14px 16px;
                }
            </style>

            <div class="table-wrapper">
                <table id="tabel-pengaduan">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Siswa</th>
                            <th>Judul Pengaduan</th>
                            <th>Kategori</th>
                            <th>Lokasi</th>
                            <th>Status</th>
                            <th>Progress</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($pengaduan as $index => $item):
                            $statusClass = match($item['status'] ?? '') {
                                'Diajukan'        => 'badge-diajukan',
                                'Diproses'        => 'badge-diproses',
                                'Selesai'         => 'badge-selesai',
                                'Ditolak'         => 'badge-ditolak',
                                default           => ''
                            };
                        ?>
                        <tr>
                            <td style="color:var(--gray-400)"><?= $index + 1 ?></td>
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
                                <div style="font-weight:600"><?= htmlspecialchars($item['judul'] ?? '-') ?></div>
                                <div style="font-size:11px;color:var(--gray-400);margin-top:2px"><?= date('d M Y, H:i', strtotime($item['created_at'])) ?></div>
                            </td>
                            <td><?= htmlspecialchars($item['nama_kategori'] ?? '-') ?></td>
                            <td><?= htmlspecialchars($item['lokasi'] ?? '-') ?></td>
                            <td><span class="status-badge <?= $statusClass ?>"><?= htmlspecialchars($item['status'] ?? '-') ?></span></td>
                            <td style="min-width:100px">
                                <div class="progress" style="margin-bottom:3px">
                                    <div class="progress-bar" style="width:<?= (int)$item['progress'] ?>%"></div>
                                </div>
                                <small style="color:var(--gray-400)"><?= (int)$item['progress'] ?>%</small>
                            </td>
                            <td>
                                <a href="<?= url('admin/pengaduan/detail/' . $item['id']) ?>" class="btn btn-sm btn-outline">Detail</a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div id="pagination-controls" style="display:flex;align-items:center;justify-content:space-between;padding:14px 20px;border-top:1px solid var(--gray-100)">
                <span id="pagination-info" style="font-size:13px;color:var(--gray-500)"></span>
                <div style="display:flex;gap:6px" id="pagination-buttons"></div>
            </div>

            <script>
            (function() {
                const perPage = 5;
                const rows    = Array.from(document.querySelectorAll('#tabel-pengaduan tbody tr'));
                const total   = rows.length;
                let   current = 1;

                function totalPages() { return Math.ceil(total / perPage); }

                function render() {
                    const start = (current - 1) * perPage;
                    const end   = start + perPage;

                    rows.forEach((r, i) => {
                        r.style.display = (i >= start && i < end) ? '' : 'none';
                    });

                    document.getElementById('pagination-info').textContent =
                        'Menampilkan ' + (Math.min(start+1, total)) + '–' + Math.min(end, total) + ' dari ' + total + ' pengaduan';

                    const btns = document.getElementById('pagination-buttons');
                    btns.innerHTML = '';

                    // Prev
                    const prev = document.createElement('button');
                    prev.textContent = '←';
                    prev.className   = 'btn btn-sm btn-secondary';
                    prev.disabled    = current === 1;
                    prev.onclick     = () => { current--; render(); };
                    btns.appendChild(prev);

                    // Page numbers
                    for (let p = 1; p <= totalPages(); p++) {
                        const btn   = document.createElement('button');
                        btn.textContent = p;
                        btn.className   = 'btn btn-sm ' + (p === current ? 'btn-primary' : 'btn-secondary');
                        btn.onclick     = ((_p) => () => { current = _p; render(); })(p);
                        btns.appendChild(btn);
                    }

                    // Next
                    const next = document.createElement('button');
                    next.textContent = '→';
                    next.className   = 'btn btn-sm btn-secondary';
                    next.disabled    = current === totalPages();
                    next.onclick     = () => { current++; render(); };
                    btns.appendChild(next);
                }

                render();
            })();
            </script>

        <?php else: ?>
            <div class="empty-state">
                <div class="empty-state-icon"><i data-lucide="clipboard-list"></i></div>
                <h3>Belum Ada Pengaduan</h3>
                <p>Belum ada pengaduan sarana prasarana yang masuk dari siswa.</p>
            </div>
        <?php endif; ?>

    </section>

<?php require_once __DIR__ . '/../../layouts/footer.php'; ?>