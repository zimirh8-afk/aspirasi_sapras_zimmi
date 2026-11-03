<?php

class ProfileController extends Controller
{
    private $userModel;

    public function __construct()
    {
        $this->userModel = new User();
    }

    /**
     * Menampilkan profil
     */
    public function index()
    {
        $this->checkAuth();

        $userId = $_SESSION['user']['id'];

        $user = $this->userModel->findById($userId);

        if (!$user) {
            $_SESSION['error'] = 'Data pengguna tidak ditemukan.';
            $this->redirect('/dashboard');
            return;
        }

        $role = $_SESSION['user']['role'];

        if ($role === 'admin') {
            $this->view('admin/profile', [
                'user' => $user
            ]);
        } else {
            $this->view('siswa/profile', [
                'user' => $user
            ]);
        }
    }

    /**
     * Update profil
     */
    public function update()
    {
        $this->checkAuth();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/profile');
            return;
        }

        $userId = $_SESSION['user']['id'];
        $nama     = trim($_POST['nama'] ?? '');
        $username = trim($_POST['username'] ?? '');

        if (empty($nama) || empty($username)) {
            $_SESSION['error'] = 'Nama dan username wajib diisi.';
            $this->redirect('/profile');
            return;
        }

        $data = ['nama' => $nama, 'username' => $username];

        // Handle foto upload
        if (isset($_FILES['foto']) && $_FILES['foto']['error'] === UPLOAD_ERR_OK) {
            $file = $_FILES['foto'];
            $allowed = ['image/jpeg', 'image/png', 'image/webp', 'image/gif'];

            if (!in_array($file['type'], $allowed)) {
                $_SESSION['error'] = 'Format foto tidak didukung. Gunakan JPG, PNG, atau WebP.';
                $this->redirect('/profile');
                return;
            }

            if ($file['size'] > 2 * 1024 * 1024) {
                $_SESSION['error'] = 'Ukuran foto maksimal 2 MB.';
                $this->redirect('/profile');
                return;
            }

            $uploadDir = __DIR__ . '/../../public/uploads/profil/';
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }

            // Hapus foto lama
            $currentUser = $this->userModel->findById($userId);
            if (!empty($currentUser['foto'])) {
                $oldFile = $uploadDir . $currentUser['foto'];
                if (file_exists($oldFile)) unlink($oldFile);
            }

            $ext      = pathinfo($file['name'], PATHINFO_EXTENSION);
            $fileName = 'profil_' . $userId . '_' . time() . '.' . $ext;

            if (move_uploaded_file($file['tmp_name'], $uploadDir . $fileName)) {
                $data['foto'] = $fileName;
            }
        }

        $result = $this->userModel->update($userId, $data);

        if ($result) {
            $_SESSION['user']['nama']     = $nama;
            $_SESSION['user']['username'] = $username;
            if (isset($data['foto'])) {
                $_SESSION['user']['foto'] = $data['foto'];
            }
            $_SESSION['success'] = 'Profil berhasil diperbarui.';
        } else {
            $_SESSION['error'] = 'Profil gagal diperbarui atau username sudah digunakan.';
        }

        $this->redirect('/profile');
    }

    /**
     * Ganti password
     */
    public function updatePassword()
    {
        $this->checkAuth();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/profile');
            return;
        }

        $userId = $_SESSION['user']['id'];

        $passwordLama = $_POST['password_lama'] ?? '';
        $passwordBaru = $_POST['password_baru'] ?? '';
        $passwordKonfirmasi = $_POST['password_konfirmasi'] ?? '';

        $user = $this->userModel->findById($userId);

        if (!$user) {
            $_SESSION['error'] = 'Data pengguna tidak ditemukan.';
            $this->redirect('/profile');
            return;
        }

        if (!password_verify($passwordLama, $user['password'])) {
            $_SESSION['error'] = 'Password lama salah.';
            $this->redirect('/profile');
            return;
        }

        if (strlen($passwordBaru) < 6) {
            $_SESSION['error'] = 'Password baru minimal 6 karakter.';
            $this->redirect('/profile');
            return;
        }

        if ($passwordBaru !== $passwordKonfirmasi) {
            $_SESSION['error'] = 'Konfirmasi password tidak sesuai.';
            $this->redirect('/profile');
            return;
        }

        $hashedPassword = password_hash(
            $passwordBaru,
            PASSWORD_DEFAULT
        );

        $result = $this->userModel->updatePassword(
            $userId,
            $hashedPassword
        );

        if ($result) {
            $_SESSION['success'] = 'Password berhasil diubah.';
        } else {
            $_SESSION['error'] = 'Password gagal diubah.';
        }

        $this->redirect('/profile');
    }
}