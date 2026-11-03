<?php

class PengaduanController extends Controller
{
    private $pengaduanModel;
    private $kategoriModel;
    private $historiModel;

    public function __construct()
    {
        $this->pengaduanModel = new Pengaduan();
        $this->kategoriModel = new Kategori();
        $this->historiModel = new HistoriPengaduan();
    }

    /**
     * Daftar pengaduan
     *
     * Admin -> semua pengaduan
     * Siswa -> pengaduan miliknya sendiri
     */
    public function index()
    {
        $this->checkAuth();

        $role = $_SESSION['user']['role'];

        if ($role === 'admin') {

            $filter = [
                'tanggal'    => $_GET['tanggal'] ?? '',
                'bulan'      => $_GET['bulan'] ?? '',
                'tahun'      => $_GET['tahun'] ?? '',
                'kategori'   => $_GET['kategori'] ?? '',
                'siswa'      => $_GET['siswa'] ?? '',
                'status'     => $_GET['status'] ?? '',
                'search'     => $_GET['search'] ?? ''
            ];

            $pengaduan = $this->pengaduanModel->getAllWithFilter($filter);

            $data = [
                'pengaduan' => $pengaduan,
                'filter'    => $filter,
                'kategori'  => $this->kategoriModel->getAll()
            ];

            $this->view('admin/pengaduan/index', $data);

        } else {

            $userId = $_SESSION['user']['id'];

            // Resolve id_siswa
            $siswaModel = new Siswa();
            $siswa = $siswaModel->findByUserId($userId);
            $siswaId = $siswa ? $siswa['id_siswa'] : $userId;

            $pengaduan = $this->pengaduanModel->getByUser($siswaId);

            $data = [
                'pengaduan' => $pengaduan
            ];

            $this->view('siswa/pengaduan/index', $data);
        }
    }

    /**
     * Form buat pengaduan
     */
    public function create()
    {
        $this->checkRole('siswa');

        $data = [
            'kategori' => $this->kategoriModel->getAll()
        ];

        $this->view('siswa/pengaduan/create', $data);
    }

    /**
     * Menyimpan pengaduan baru
     */
    public function store()
    {
        $this->checkRole('siswa');

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/siswa/pengaduan/create');
            return;
        }

        $judul      = trim($_POST['judul'] ?? '');
        $kategoriId = $_POST['kategori_id'] ?? '';
        $lokasi     = trim($_POST['lokasi'] ?? '');
        $deskripsi  = trim($_POST['deskripsi'] ?? '');

        if (empty($judul) || empty($kategoriId) || empty($lokasi) || empty($deskripsi)) {
            $_SESSION['error'] = 'Semua field wajib diisi.';
            $this->redirect('/siswa/pengaduan/create');
            return;
        }

        // Resolve id_siswa dari id_user (karena FK aspirasi -> siswa)
        $siswaModel = new Siswa();
        $siswa = $siswaModel->findByUserId($_SESSION['user']['id']);

        if (!$siswa) {
            $_SESSION['error'] = 'Data siswa tidak ditemukan. Hubungi admin.';
            $this->redirect('/siswa/pengaduan/create');
            return;
        }

        $userId = $siswa['id_siswa'];

        // Upload foto
        $foto = null;
        if (isset($_FILES['foto']) && $_FILES['foto']['error'] === UPLOAD_ERR_OK) {
            $foto = $this->uploadFoto($_FILES['foto']);
        }

        $data = [
            'user_id'     => $userId,
            'kategori_id' => $kategoriId,
            'judul'       => $judul,
            'lokasi'      => $lokasi,
            'deskripsi'   => $deskripsi,
            'foto'        => $foto,
            'status'      => 'Diajukan',
            'progress'    => 0
        ];

        $pengaduanId = $this->pengaduanModel->create($data);

        if ($pengaduanId) {
            $this->historiModel->create([
                'pengaduan_id' => $pengaduanId,
                'status'       => 'Diajukan',
                'progress'     => 0,
                'keterangan'   => 'Pengaduan berhasil dibuat oleh siswa.'
            ]);

            // Kirim notifikasi ke semua admin (dengan try-catch)
            try {
                require_once __DIR__ . '/../helpers/EmailHelper.php';
                require_once __DIR__ . '/../models/Notifikasi.php';
                
                $notifikasiModel = new Notifikasi();
                $userModel = new User();
                
                // Ambil data kategori
                $kategoriData = $this->kategoriModel->getById($kategoriId);
                $namaKategori = $kategoriData['nama'] ?? '-';
                
                // Kirim notifikasi ke semua admin
                $admins = $userModel->getAllAdmins();
                foreach ($admins as $admin) {
                    // Notifikasi in-app
                    $notifikasiModel->create([
                        'user_id'       => $admin['id'],
                        'pengaduan_id'  => $pengaduanId,
                        'judul'         => 'Pengaduan Baru',
                        'pesan'         => $siswa['nama'] . ' membuat pengaduan baru: ' . $judul,
                        'tipe'          => 'pengaduan_baru'
                    ]);
                    
                    // Kirim email ke admin (jika ada email)
                    if (!empty($admin['email'])) {
                        EmailHelper::sendNewPengaduan(
                            $admin['email'],
                            $admin['nama'],
                            $siswa['nama'],
                            $judul,
                            $namaKategori
                        );
                    }
                }
            } catch (Exception $e) {
                // Log error tapi jangan block proses
                error_log('Error sending notification: ' . $e->getMessage());
            }

            $_SESSION['success'] = 'Pengaduan berhasil dikirim.';
            $this->redirect('/siswa/pengaduan');
        } else {
            $_SESSION['error'] = 'Pengaduan gagal dikirim.';
            $this->redirect('/siswa/pengaduan/create');
        }
    }

    /**
     * Detail pengaduan
     */
    public function detail($id)
    {
        $this->checkAuth();

        $pengaduan = $this->pengaduanModel->getById($id);

        if (!$pengaduan) {
            $_SESSION['error'] = 'Pengaduan tidak ditemukan.';
            $this->redirect('/dashboard');
            return;
        }

        // Siswa hanya boleh melihat pengaduannya sendiri
        if ($_SESSION['user']['role'] === 'siswa') {

            $siswaModel = new Siswa();
            $siswa = $siswaModel->findByUserId($_SESSION['user']['id']);
            $siswaId = $siswa ? $siswa['id_siswa'] : $_SESSION['user']['id'];

            if ($pengaduan['user_id'] != $siswaId) {
                $_SESSION['error'] = 'Anda tidak memiliki akses.';
                $this->redirect('/siswa/pengaduan');
                return;
            }

            $histori = $this->historiModel->getByPengaduan($id);

            $data = [
                'pengaduan' => $pengaduan,
                'histori'   => $histori
            ];

            $this->view('siswa/pengaduan/detail', $data);

        } else {

            $histori = $this->historiModel->getByPengaduan($id);

            $data = [
                'pengaduan' => $pengaduan,
                'histori'   => $histori
            ];

            $this->view('admin/pengaduan/detail', $data);
        }
    }

    /**
     * Admin mengubah status dan progress
     */
    public function updateStatus($id)
    {
        $this->checkRole('admin');

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/admin/pengaduan/detail/' . $id);
            return;
        }

        $status     = $_POST['status'] ?? '';
        $progress   = (int) ($_POST['progress'] ?? 0);
        $keterangan = trim($_POST['keterangan'] ?? '');
        $umpanBalik = trim($_POST['umpan_balik'] ?? '');

        $allowedStatus = ['Diajukan', 'Diproses', 'Selesai', 'Ditolak'];

        if (!in_array($status, $allowedStatus)) {
            $_SESSION['error'] = 'Status tidak valid.';
            $this->redirect('/admin/pengaduan/detail/' . $id);
            return;
        }

        $progress = max(0, min(100, $progress));
        if ($status === 'Selesai') $progress = 100;

        $this->pengaduanModel->updateStatus($id, $status, $progress);

        $this->historiModel->create([
            'pengaduan_id' => $id,
            'status'       => $status,
            'progress'     => $progress,
            'keterangan'   => $keterangan
        ]);

        // Simpan umpan balik jika diisi
        if (!empty($umpanBalik)) {
            $feedbackModel = new Feedback();
            $feedbackModel->create([
                'pengaduan_id' => $id,
                'admin_id'     => $_SESSION['user']['id'],
                'isi'          => $umpanBalik
            ]);
        }

        // Kirim notifikasi ke siswa
        $pengaduanData = $this->pengaduanModel->getById($id);
        if ($pengaduanData) {
            require_once __DIR__ . '/../helpers/EmailHelper.php';
            require_once __DIR__ . '/../models/Notifikasi.php';
            
            $notifikasiModel = new Notifikasi();
            
            // Tentukan pesan berdasarkan status
            $pesanStatus = [
                'Diajukan'  => 'Pengaduan Anda telah diajukan dan sedang menunggu verifikasi.',
                'Diproses'  => 'Pengaduan Anda sedang dalam proses penanganan.',
                'Selesai'   => 'Pengaduan Anda telah selesai ditangani.',
                'Ditolak'   => 'Maaf, pengaduan Anda ditolak. Silakan cek detail untuk informasi lebih lanjut.'
            ];

            // Kirim notifikasi in-app
            $notifikasiModel->create([
                'user_id'       => $pengaduanData['user_id'],
                'pengaduan_id'  => $id,
                'judul'         => 'Update Status Pengaduan',
                'pesan'         => $pesanStatus[$status] ?? 'Status pengaduan Anda telah diperbarui.',
                'tipe'          => 'status_update'
            ]);

            // Kirim email ke siswa (jika ada email)
            $siswaModel = new Siswa();
            $siswaData = $siswaModel->findByIdSiswa($pengaduanData['user_id']);
            
            // AMBIL ID USER dari tabel siswa untuk notifikasi
            if ($siswaData) {
                // Kirim notifikasi ke id_user yang benar
                $notifikasiModel->create([
                    'user_id'       => $siswaData['id_user'],
                    'pengaduan_id'  => $id,
                    'judul'         => 'Update Status Pengaduan',
                    'pesan'         => $pesanStatus[$status] ?? 'Status pengaduan Anda telah diperbarui.',
                    'tipe'          => 'status_update'
                ]);
                
                // Kirim email jika ada
                if (!empty($siswaData['email'])) {
                    EmailHelper::sendStatusUpdate(
                        $siswaData['email'],
                        $pengaduanData['nama_siswa'],
                        $pengaduanData['judul'],
                        $status,
                        $keterangan
                    );
                }
            }
        }

        $_SESSION['success'] = 'Pengaduan berhasil diperbarui.';
        $this->redirect('/admin/pengaduan/detail/' . $id);
    }

    /**
     * Halaman edit pengaduan (admin)
     */
    public function edit($id)
    {
        $this->checkRole('admin');

        $pengaduan = $this->pengaduanModel->getById($id);

        if (!$pengaduan) {
            $_SESSION['error'] = 'Pengaduan tidak ditemukan.';
            $this->redirect('/admin/pengaduan');
            return;
        }

        $this->view('admin/pengaduan/edit', [
            'pengaduan' => $pengaduan
        ]);
    }

    /**
     * Histori pengaduan siswa
     */
    public function historiSiswa()
    {
        $this->checkRole('siswa');

        $userId = $_SESSION['user']['id'];

        // Resolve id_siswa
        $siswaModel = new Siswa();
        $siswa = $siswaModel->findByUserId($userId);
        $siswaId = $siswa ? $siswa['id_siswa'] : $userId;

        $pengaduan = $this->pengaduanModel->getByUser($siswaId);

        $this->view('siswa/histori/index', [
            'pengaduan' => $pengaduan
        ]);
    }

    /**
     * Histori pengaduan admin
     */
    public function historiAdmin()
    {
        $this->checkRole('admin');

        $pengaduan = $this->pengaduanModel->getAllWithFilter([]);

        $this->view('admin/histori/index', [
            'feedback' => $pengaduan
        ]);
    }

    /**
     * Edit pengaduan siswa
     */
    public function editSiswa($id)
    {
        $this->checkRole('siswa');

        $pengaduan = $this->pengaduanModel->getById($id);

        if (!$pengaduan) {
            $_SESSION['error'] = 'Pengaduan tidak ditemukan.';
            $this->redirect('/siswa/pengaduan');
            return;
        }

        // Validasi kepemilikan
        $siswaModel = new Siswa();
        $siswa = $siswaModel->findByUserId($_SESSION['user']['id']);
        $siswaId = $siswa ? $siswa['id_siswa'] : 0;

        if ($pengaduan['user_id'] != $siswaId) {
            $_SESSION['error'] = 'Tidak memiliki akses.';
            $this->redirect('/siswa/pengaduan');
            return;
        }

        // Hanya bisa edit jika masih Diajukan
        if ($pengaduan['status'] !== 'Diajukan') {
            $_SESSION['error'] = 'Pengaduan yang sudah diproses tidak bisa diedit.';
            $this->redirect('/siswa/pengaduan');
            return;
        }

        $this->view('siswa/pengaduan/edit', [
            'pengaduan' => $pengaduan,
            'kategori'  => $this->kategoriModel->getAll()
        ]);
    }

    /**
     * Proses edit pengaduan siswa
     */
    public function updateSiswa($id)
    {
        $this->checkRole('siswa');

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/siswa/pengaduan');
            return;
        }

        $pengaduan = $this->pengaduanModel->getById($id);

        if (!$pengaduan) {
            $_SESSION['error'] = 'Pengaduan tidak ditemukan.';
            $this->redirect('/siswa/pengaduan');
            return;
        }

        $siswaModel = new Siswa();
        $siswa = $siswaModel->findByUserId($_SESSION['user']['id']);
        $siswaId = $siswa ? $siswa['id_siswa'] : 0;

        if ($pengaduan['user_id'] != $siswaId || $pengaduan['status'] !== 'Diajukan') {
            $_SESSION['error'] = 'Tidak bisa mengedit pengaduan ini.';
            $this->redirect('/siswa/pengaduan');
            return;
        }

        $data = [
            'judul'       => trim($_POST['judul'] ?? ''),
            'kategori_id' => $_POST['kategori_id'] ?? '',
            'lokasi'      => trim($_POST['lokasi'] ?? ''),
            'deskripsi'   => trim($_POST['deskripsi'] ?? ''),
        ];

        if (empty($data['judul']) || empty($data['kategori_id']) || empty($data['lokasi']) || empty($data['deskripsi'])) {
            $_SESSION['error'] = 'Semua field wajib diisi.';
            $this->redirect('/siswa/pengaduan/edit/' . $id);
            return;
        }

        $result = $this->pengaduanModel->updateData($id, $data);

        if ($result) {
            $_SESSION['success'] = 'Pengaduan berhasil diperbarui.';
        } else {
            $_SESSION['error'] = 'Gagal memperbarui pengaduan.';
        }

        $this->redirect('/siswa/pengaduan');
    }

    /**
     * Hapus pengaduan siswa
     */
    public function deleteSiswa($id)
    {
        $this->checkRole('siswa');

        $pengaduan = $this->pengaduanModel->getById($id);

        if (!$pengaduan) {
            $_SESSION['error'] = 'Pengaduan tidak ditemukan.';
            $this->redirect('/siswa/pengaduan');
            return;
        }

        $siswaModel = new Siswa();
        $siswa = $siswaModel->findByUserId($_SESSION['user']['id']);
        $siswaId = $siswa ? $siswa['id_siswa'] : 0;

        if ($pengaduan['user_id'] != $siswaId) {
            $_SESSION['error'] = 'Tidak memiliki akses.';
            $this->redirect('/siswa/pengaduan');
            return;
        }

        if ($pengaduan['status'] !== 'Diajukan') {
            $_SESSION['error'] = 'Pengaduan yang sudah diproses tidak bisa dihapus.';
            $this->redirect('/siswa/pengaduan');
            return;
        }

        $this->pengaduanModel->delete($id);

        $_SESSION['success'] = 'Pengaduan berhasil dihapus.';
        $this->redirect('/siswa/pengaduan');
    }
    /**
     * Upload foto pengaduan
     */
    private function uploadFoto($file)
    {
        $allowedTypes = [
            'image/jpeg',
            'image/png',
            'image/webp'
        ];

        if (!in_array($file['type'], $allowedTypes)) {
            return null;
        }

        // Maksimal 5 MB
        if ($file['size'] > 5 * 1024 * 1024) {
            return null;
        }

        $uploadDir = __DIR__ . '/../../public/uploads/pengaduan/';

        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        $extension = pathinfo(
            $file['name'],
            PATHINFO_EXTENSION
        );

        $fileName = uniqid('pengaduan_', true) . '.' . $extension;

        $target = $uploadDir . $fileName;

        if (move_uploaded_file($file['tmp_name'], $target)) {
            return $fileName;
        }

        return null;
    }
}