<?php
$user = $_SESSION['user'] ?? null;
$role = $user['role'] ?? '';
$currentUrl = $_SERVER['REQUEST_URI'] ?? '';

function isActive($path) {
    $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
    $uri = rtrim($uri, '/');
    $basePath = rtrim(parse_url(BASE_URL, PHP_URL_PATH), '/');
    $check = $basePath . '/' . ltrim($path, '/');
    $check = rtrim($check, '/');
    return ($uri === $check || strpos($uri, $check . '/') === 0) ? 'active' : '';
}
?>

<aside class="sidebar" id="sidebar">

    <div class="sidebar-brand">
        <img src="<?= url('logo.png') ?>" alt="SIADU" style="height:52px;object-fit:contain;padding:2px 0">
        <button class="sidebar-close" id="sidebarClose" onclick="closeSidebar()">✕</button>
    </div>

    <?php if ($user): ?>
    <div class="sidebar-user">
        <?php
        $fotoSidebar = $user['foto'] ?? ($_SESSION['user']['foto'] ?? null);
        $nisDisplay  = '';
        $unreadNotif = 0;
        
        if ($role === 'siswa') {
            $siswaModel  = new Siswa();
            $siswaData   = $siswaModel->findByUserId($user['id']);
            $nisDisplay  = $siswaData['nis'] ?? '';
        }
        
        // Hitung notifikasi belum dibaca (dengan try-catch untuk handling jika tabel belum ada)
        try {
            require_once __DIR__ . '/../../models/Notifikasi.php';
            $notifikasiModel = new Notifikasi();
            $unreadNotif = $notifikasiModel->countUnread($user['id']);
        } catch (Exception $e) {
            $unreadNotif = 0;
        }
        ?>
        <div class="sidebar-avatar" style="<?= $fotoSidebar ? 'padding:0;overflow:hidden' : '' ?>">
            <?php if ($fotoSidebar): ?>
                <img src="<?= url('public/uploads/profil/' . htmlspecialchars($fotoSidebar)) ?>" style="width:100%;height:100%;object-fit:cover">
            <?php else: ?>
                <?= strtoupper(substr($user['nama'], 0, 1)) ?>
            <?php endif; ?>
        </div>
        <div class="sidebar-user-info">
            <div class="sidebar-user-name"><?= htmlspecialchars($user['nama']) ?></div>
            <?php if ($nisDisplay): ?>
                <div class="sidebar-user-role">NIS: <?= htmlspecialchars($nisDisplay) ?></div>
            <?php else: ?>
                <div class="sidebar-user-role"><?= ucfirst($user['role']) ?></div>
            <?php endif; ?>
        </div>
        <!-- Notifikasi Bell -->
        <div style="position:relative;margin-left:auto">
            <button onclick="toggleNotifPanel()" style="background:none;border:none;cursor:pointer;position:relative;padding:8px">
                <i data-lucide="bell" style="width:20px;height:20px;color:var(--gray-600)"></i>
                <?php if ($unreadNotif > 0): ?>
                <span class="notification-dot" style="position:absolute;top:4px;right:4px;width:18px;height:18px;background:var(--danger);color:white;border-radius:50%;font-size:10px;font-weight:700;display:flex;align-items:center;justify-content:center">
                    <?= $unreadNotif > 9 ? '9+' : $unreadNotif ?>
                </span>
                <?php endif; ?>
            </button>
        </div>
    </div>
    <?php endif; ?>

    <nav class="sidebar-nav">

        <?php if ($role === 'admin'): ?>
        <?php
        $userModel = new User();
        $pendingCount = $userModel->countPending();
        ?>

            <div class="sidebar-nav-label">Menu</div>

            <a href="<?= url('admin/dashboard') ?>" class="<?= isActive('admin/dashboard') ?>">
                <i data-lucide="layout-dashboard" class="nav-icon"></i> Dashboard
            </a>
            <a href="<?= url('admin/pengaduan') ?>" class="<?= isActive('admin/pengaduan') ?>">
                <i data-lucide="clipboard-list" class="nav-icon"></i> Pengaduan
            </a>
            <a href="<?= url('admin/verifikasi') ?>" class="<?= isActive('admin/verifikasi') ?>" style="justify-content:space-between">
                <span style="display:flex;align-items:center;gap:10px">
                    <i data-lucide="user-check" class="nav-icon"></i> Verifikasi Akun
                </span>
                <?php if ($pendingCount > 0): ?>
                    <span style="background:var(--danger);color:white;border-radius:20px;padding:1px 7px;font-size:11px;font-weight:700"><?= $pendingCount ?></span>
                <?php endif; ?>
            </a>
            <a href="<?= url('admin/feedback') ?>" class="<?= isActive('admin/feedback') ?>">
                <i data-lucide="message-square" class="nav-icon"></i> Feedback
            </a>
            <a href="<?= url('admin/histori') ?>" class="<?= isActive('admin/histori') ?>">
                <i data-lucide="history" class="nav-icon"></i> Histori
            </a>

            <div class="sidebar-nav-label">Akun</div>

            <a href="<?= url('admin/profile') ?>" class="<?= isActive('admin/profile') ?>">
                <i data-lucide="user" class="nav-icon"></i> Profil
            </a>
            <a href="#" onclick="confirmLogout(event)" style="color:var(--danger)">
                <i data-lucide="log-out" class="nav-icon"></i> Logout
            </a>

        <?php else: ?>

            <div class="sidebar-nav-label">Menu</div>

            <a href="<?= url('siswa/dashboard') ?>" class="<?= isActive('siswa/dashboard') ?>">
                <i data-lucide="layout-dashboard" class="nav-icon"></i> Dashboard
            </a>
            <a href="<?= url('siswa/pengaduan') ?>" class="<?= isActive('siswa/pengaduan') && !isActive('siswa/pengaduan/create') && !isActive('siswa/pengaduan/edit') && !isActive('siswa/pengaduan/detail') ? 'active' : '' ?>">
                <i data-lucide="clipboard-list" class="nav-icon"></i> Pengaduan Saya
            </a>
            <a href="<?= url('siswa/pengaduan/create') ?>" class="<?= isActive('siswa/pengaduan/create') ?>">
                <i data-lucide="plus-circle" class="nav-icon"></i> Buat Pengaduan
            </a>
            <a href="<?= url('siswa/feedback') ?>" class="<?= isActive('siswa/feedback') ?>">
                <i data-lucide="message-square" class="nav-icon"></i> Feedback
            </a>
            <a href="<?= url('siswa/histori') ?>" class="<?= isActive('siswa/histori') ?>">
                <i data-lucide="history" class="nav-icon"></i> Histori
            </a>

            <div class="sidebar-nav-label">Akun</div>

            <a href="<?= url('siswa/profile') ?>" class="<?= isActive('siswa/profile') ?>">
                <i data-lucide="user" class="nav-icon"></i> Profil
            </a>
            <a href="#" onclick="confirmLogout(event)" style="color:var(--danger)">
                <i data-lucide="log-out" class="nav-icon"></i> Logout
            </a>

        <?php endif; ?>

    </nav>

    <div class="sidebar-footer" style="margin-top:auto;border-top:1px solid var(--gray-100);position:sticky;bottom:0;background:white;z-index:1">
        <a href="#" onclick="confirmLogout(event)">
            <i data-lucide="log-out" class="nav-icon"></i> Logout
        </a>
    </div>

</aside>

<!-- Logout Confirmation Modal -->
<div id="logoutModal" class="modal-overlay" style="display:none;position:fixed;top:0;left:0;right:0;bottom:0;background:rgba(0,0,0,0.5);z-index:9999;align-items:center;justify-content:center">
    <div class="modal-content" style="background:white;border-radius:var(--radius-lg);padding:24px;max-width:360px;width:90%;box-shadow:0 20px 50px rgba(0,0,0,0.2);text-align:center">
        <div style="width:60px;height:60px;border-radius:50%;background:linear-gradient(135deg,#ff6b6b,#ee5a5a);display:flex;align-items:center;justify-content:center;margin:0 auto 16px">
            <i data-lucide="log-out" style="width:28px;height:28px;color:white;stroke-width:2"></i>
        </div>
        <h3 style="font-size:18px;font-weight:700;color:var(--gray-800);margin:0 0 8px">Yakin untuk logout?</h3>
        <p style="font-size:13px;color:var(--gray-500);margin:0 0 24px">Anda akan keluar dari sistem dan kembali ke halaman login.</p>
        <div style="display:flex;gap:10px">
            <button onclick="closeLogoutModal()" style="flex:1;padding:12px;border:1px solid var(--gray-200);background:white;border-radius:var(--radius);font-weight:600;font-size:14px;color:var(--gray-600);cursor:pointer">Tidak</button>
            <a href="<?= url('logout') ?>" style="flex:1;padding:12px;background:linear-gradient(135deg,#ff6b6b,#ee5a5a);border-radius:var(--radius);font-weight:600;font-size:14px;color:white;text-decoration:none;display:flex;align-items:center;justify-content:center">Iya</a>
        </div>
    </div>
</div>

<!-- Notification Panel -->
<div id="notifPanel" style="display:none;position:fixed;top:70px;right:20px;width:360px;max-height:500px;background:white;border-radius:var(--radius-lg);box-shadow:0 10px 40px rgba(0,0,0,0.15);z-index:1000;overflow:hidden">
    <div style="padding:16px 20px;border-bottom:1px solid var(--gray-100);display:flex;justify-content:space-between;align-items:center">
        <h3 style="font-size:16px;font-weight:700">Notifikasi</h3>
        <button onclick="markAllRead()" style="font-size:12px;color:var(--primary);background:none;border:none;cursor:pointer">Tandai dibaca</button>
    </div>
    <div id="notifList" style="max-height:400px;overflow-y:auto">
        <!-- Diisi via JavaScript -->
    </div>
</div>

<script>
function confirmLogout(e) {
    e.preventDefault();
    document.getElementById('logoutModal').style.display = 'flex';
}

function closeLogoutModal() {
    document.getElementById('logoutModal').style.display = 'none';
}

// Toggle notification panel
function toggleNotifPanel() {
    const panel = document.getElementById('notifPanel');
    if (panel.style.display === 'none') {
        panel.style.display = 'block';
        loadNotifications();
    } else {
        panel.style.display = 'none';
    }
}

// Load notifications via AJAX
function loadNotifications() {
    fetch('<?= url("api/notifikasi") ?>')
        .then(res => res.json())
        .then(data => {
            const list = document.getElementById('notifList');
            if (data.length === 0) {
                list.innerHTML = '<div style="padding:40px 20px;text-align:center;color:var(--gray-400)"><i data-lucide="bell-off" style="width:32px;height:32px;margin-bottom:10px;opacity:0.5"></i><p>Belum ada notifikasi</p></div>';
            } else {
                list.innerHTML = data.map(n => `
                    <div class="notif-item ${n.status === 'belum' ? 'unread' : ''}" 
                         style="padding:14px 20px;border-bottom:1px solid var(--gray-100);cursor:pointer;${n.status === 'belum' ? 'background:var(--primary-bg)' : ''}"
                         onclick="viewNotif(${n.id}, ${n.pengaduan_id})">
                        <div style="font-weight:600;font-size:13px;margin-bottom:4px"><?= htmlspecialchars($user['nama']) ?>${n.judul}</div>
                        <div style="font-size:12px;color:var(--gray-500);margin-bottom:6px">${n.pesan}</div>
                        <div style="font-size:11px;color:var(--gray-400)">${n.created_at}</div>
                    </div>
                `).join('');
            }
            lucide.createIcons();
        })
        .catch(err => {
            console.error('Error loading notifications:', err);
        });
}

// Mark all as read
function markAllRead() {
    fetch('<?= url("api/notifikasi/mark-all") ?>', { method: 'POST' })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                // Update badge count - hide badge
                const badge = document.querySelector('.notification-dot');
                if (badge) {
                    badge.style.display = 'none';
                }
                // Clear notification panel
                const notifList = document.getElementById('notifList');
                notifList.innerHTML = '<div style="padding:40px 20px;text-align:center;color:var(--gray-400)"><i data-lucide="bell-off" style="width:32px;height:32px;margin-bottom:10px;opacity:0.5"></i><p>Semua notifikasi sudah dibaca</p></div>';
                lucide.createIcons();
                // Show success toast
                showToast('Semua notifikasi ditandai sudah dibaca', 'success');
            } else {
                showToast(data.message || 'Gagal menandai notifikasi', 'error');
            }
        })
        .catch(err => {
            console.error('Error:', err);
            showToast('Terjadi kesalahan', 'error');
        });
}

// View notification
function viewNotif(id, pengaduanId) {
    fetch('<?= url("api/notifikasi/read/") ?>' + id, { method: 'POST' })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                // Update badge count
                const badge = document.querySelector('.notification-dot');
                if (badge) {
                    const currentCount = parseInt(badge.textContent);
                    if (currentCount > 1) {
                        badge.textContent = currentCount - 1;
                    } else {
                        badge.style.display = 'none';
                    }
                }
                
                // Redirect ke detail pengaduan
                if (pengaduanId) {
                    window.location.href = '<?= url("admin/pengaduan/detail/") ?>' + pengaduanId;
                }
            }
        });
}

// Close notification panel when clicking outside
document.addEventListener('click', function(e) {
    const panel = document.getElementById('notifPanel');
    const bell = e.target.closest('[onclick="toggleNotifPanel()"]');
    if (!panel.contains(e.target) && !bell) {
        panel.style.display = 'none';
    }
});

// Close modal when clicking outside
document.getElementById('logoutModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeLogoutModal();
    }
});
</script>

<div class="main-content">
    <!-- Mobile topbar -->
    <div class="mobile-topbar">
        <button onclick="openSidebar()" class="hamburger-btn">
            <span></span><span></span><span></span>
        </button>
        <img src="<?= url('logo.png') ?>" alt="SIADU" style="height:36px;object-fit:contain">
        <div style="width:40px"></div>
    </div>
    <div class="sidebar-overlay" id="sidebarOverlay" onclick="closeSidebar()"></div>
    <div class="page-body">
