<?php

class VerifikasiController extends Controller
{
    private $userModel;

    public function __construct()
    {
        $this->userModel = new User();
    }

    /**
     * Daftar akun siswa pending
     */
    public function index()
    {
        $this->checkRole('admin');

        $allSiswa = $this->userModel->getAllSiswaWithStatus();

        $this->view('admin/verifikasi/index', [
            'siswa' => $allSiswa
        ]);
    }

    /**
     * Approve akun siswa
     */
    public function approve($id)
    {
        $this->checkRole('admin');

        $result = $this->userModel->verifikasi($id);

        if ($result) {
            $_SESSION['success'] = 'Akun siswa berhasil diverifikasi.';
        } else {
            $_SESSION['error'] = 'Gagal memverifikasi akun.';
        }

        $this->redirect('/admin/verifikasi');
    }

    /**
     * Tolak akun siswa
     */
    public function tolak($id)
    {
        $this->checkRole('admin');

        $result = $this->userModel->tolak($id);

        if ($result) {
            $_SESSION['success'] = 'Akun siswa berhasil ditolak dan dihapus.';
        } else {
            $_SESSION['error'] = 'Gagal menolak akun.';
        }

        $this->redirect('/admin/verifikasi');
    }
}
