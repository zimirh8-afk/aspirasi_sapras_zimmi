<?php

class DashboardController extends Controller
{
    private $pengaduanModel;

    public function __construct()
    {
        $this->pengaduanModel = new Pengaduan();
    }

    /**
     * Dashboard utama
     */
    public function index()
    {
        $this->checkAuth();

        $role = $_SESSION['user']['role'];

        if ($role === 'admin') {
            $this->admin();
        } else {
            $this->siswa();
        }
    }

    /**
     * Dashboard Admin
     */
    public function admin()
    {
        $this->checkRole('admin');

        $userModel = new User();

        $data = [
            'totalPengaduan' => $this->pengaduanModel->countAll(),
            'menunggu'       => $this->pengaduanModel->countByStatus('Diajukan'),
            'diproses'       => $this->pengaduanModel->countByStatus('Diproses'),
            'selesai'        => $this->pengaduanModel->countByStatus('Selesai'),
            'terbaru'        => $this->pengaduanModel->getLatest(5),
            'pendingAkun'    => $userModel->countPending(),
            'monthlyStats'   => $this->pengaduanModel->getMonthlyStats(12),
        ];

        $this->view('admin/dashboard', $data);
    }

    /**
     * Dashboard Siswa
     */
    public function siswa()
    {
        $this->checkRole('siswa');

        $userId = $_SESSION['user']['id'];

        // Resolve id_siswa dari id_user
        $siswaModel = new Siswa();
        $siswa = $siswaModel->findByUserId($userId);
        $siswaId = $siswa ? $siswa['id_siswa'] : $userId;

        $data = [
            'totalPengaduan' => $this->pengaduanModel->countByUser($siswaId),
            'diproses'       => $this->pengaduanModel->countByUserAndStatus($siswaId, 'Diproses'),
            'selesai'        => $this->pengaduanModel->countByUserAndStatus($siswaId, 'Selesai'),
            'terbaru'        => $this->pengaduanModel->getLatestByUser($siswaId, 5)
        ];

        $this->view('siswa/dashboard', $data);
    }
}