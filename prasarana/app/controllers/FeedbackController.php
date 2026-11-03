<?php

class FeedbackController extends Controller
{
    private $feedbackModel;
    private $pengaduanModel;

    public function __construct()
    {
        $this->feedbackModel = new Feedback();
        $this->pengaduanModel = new Pengaduan();
    }

    /**
     * Menampilkan feedback
     *
     * Admin -> semua feedback
     * Siswa -> feedback pengaduannya sendiri
     */
    public function index()
    {
        $this->checkAuth();

        $role = $_SESSION['user']['role'];

        if ($role === 'admin') {

            $feedback = $this->feedbackModel->getAll();

            $data = [
                'feedback' => $feedback
            ];

            $this->view('admin/feedback/index', $data);

        } else {

            $userId = $_SESSION['user']['id'];

            $feedback = $this->feedbackModel->getByUser($userId);

            $data = [
                'feedback' => $feedback
            ];

            $this->view('siswa/feedback/index', $data);
        }
    }

    /**
     * Admin memberikan feedback
     */
    public function store()
    {
        $this->checkRole('admin');

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/admin/feedback');
            return;
        }

        $pengaduanId = $_POST['pengaduan_id'] ?? '';
        $isi = trim($_POST['isi'] ?? '');

        if (empty($pengaduanId) || empty($isi)) {
            $_SESSION['error'] = 'Feedback tidak boleh kosong.';
            $this->redirect('/admin/pengaduan/detail/' . $pengaduanId);
            return;
        }

        // Pastikan pengaduan tersedia
        $pengaduan = $this->pengaduanModel->getById($pengaduanId);

        if (!$pengaduan) {
            $_SESSION['error'] = 'Pengaduan tidak ditemukan.';
            $this->redirect('/admin/pengaduan');
            return;
        }

        $data = [
            'pengaduan_id' => $pengaduanId,
            'admin_id'     => $_SESSION['user']['id'],
            'isi'          => $isi
        ];

        $result = $this->feedbackModel->create($data);

        if ($result) {
            $_SESSION['success'] = 'Feedback berhasil dikirim.';
        } else {
            $_SESSION['error'] = 'Feedback gagal dikirim.';
        }

        $this->redirect(
            '/admin/pengaduan/detail/' . $pengaduanId
        );
    }
}