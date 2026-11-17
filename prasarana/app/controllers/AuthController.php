<?php

class AuthController extends Controller
{
    private $userModel;

    public function __construct()
    {
        $this->userModel = new User();
    }

    /**
     * Menampilkan halaman login
     */
    public function login()
    {
        // Jika sudah login, arahkan ke dashboard
        if (isset($_SESSION['user'])) {
            $this->redirect('/dashboard');
            return;
        }

        $this->view('auth/login');
    }

    /**
     * Memproses login
     */
    public function authenticate()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/login');
            return;
        }

        $identifier = trim($_POST['username'] ?? ''); // bisa username atau NIS
        $password   = $_POST['password'] ?? '';

        if (empty($identifier) || empty($password)) {
            $_SESSION['error'] = 'Username/NIS dan password wajib diisi.';
            $this->redirect('/login');
            return;
        }

        // Coba cari sebagai admin (pakai username)
        $user = $this->userModel->findByUsername($identifier);

        // Kalau tidak ketemu, coba cari sebagai siswa via NIS
        if (!$user) {
            $siswaModel = new Siswa();
            $siswa = $siswaModel->findByNis($identifier);
            if ($siswa) {
                $user = $this->userModel->findById($siswa['id_user']);
            }
        }

        if (!$user) {
            $_SESSION['error'] = 'Username/NIS atau password salah.';
            $this->redirect('/login');
            return;
        }

        if (!password_verify($password, $user['password'])) {
            $_SESSION['error'] = 'Username/NIS atau password salah.';
            $this->redirect('/login');
            return;
        }

        if (isset($user['status_akun']) && $user['status_akun'] === 'pending') {
            $_SESSION['error'] = 'Akun kamu belum diverifikasi oleh admin. Silakan tunggu konfirmasi.';
            $this->redirect('/login');
            return;
        }

        session_regenerate_id(true);

        $_SESSION['user'] = [
            'id'       => $user['id'],
            'nama'     => $user['nama'],
            'username' => $user['username'],
            'role'     => $user['role'],
            'foto'     => $user['foto'] ?? null
        ];

        $_SESSION['success'] = 'Login berhasil. Selamat datang!';

        if ($user['role'] === 'admin') {
            $this->redirect('/admin/dashboard');
        } else {
            $this->redirect('/siswa/dashboard');
        }
    }

    /**
     * Menampilkan halaman register
     */
    public function register()
    {
        if (isset($_SESSION['user'])) {
            $this->redirect('/dashboard');
            return;
        }

        $this->view('auth/register');
    }

    /**
     * Memproses registrasi siswa
     */
    public function store()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/register');
            return;
        }

        $nama            = trim($_POST['nama'] ?? '');
        $nis             = trim($_POST['nis'] ?? '');
        $email           = trim($_POST['email'] ?? '');
        $username        = trim($_POST['username'] ?? '');
        $password        = $_POST['password'] ?? '';
        $passwordConfirm = $_POST['password_confirm'] ?? '';

        if (empty($nama) || empty($nis) || empty($username) || empty($password) || empty($passwordConfirm)) {
            $_SESSION['error'] = 'Semua field wajib diisi.';
            $this->redirect('/register');
            return;
        }

        if ($password !== $passwordConfirm) {
            $_SESSION['error'] = 'Konfirmasi password tidak sesuai.';
            $this->redirect('/register');
            return;
        }

        if (strlen($password) < 6) {
            $_SESSION['error'] = 'Password minimal 6 karakter.';
            $this->redirect('/register');
            return;
        }

        // Cek NIS sudah dipakai
        $siswaModel = new Siswa();
        if ($siswaModel->findByNis($nis)) {
            $_SESSION['error'] = 'NIS sudah terdaftar.';
            $this->redirect('/register');
            return;
        }

        // Cek username sudah dipakai
        if ($this->userModel->findByUsername($username)) {
            $_SESSION['error'] = 'Username sudah digunakan.';
            $this->redirect('/register');
            return;
        }

        // Validasi email format jika diisi
        if (!empty($email) && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $_SESSION['error'] = 'Format email tidak valid.';
            $this->redirect('/register');
            return;
        }

        // Buat user baru
        $userData = [
            'nama'        => $nama,
            'username'    => $username,
            'password'    => password_hash($password, PASSWORD_BCRYPT),
            'role'        => 'siswa',
            'status_akun' => 'pending',
            'email'       => $email ?: null
        ];

        $userId = $this->userModel->create($userData);

        if ($userId) {
            $siswaModel->create([
                'id_user'    => $userId,
                'nis'        => $nis,
                'nama_siswa' => $nama,
                'kelas'      => ''
            ]);

            $_SESSION['success'] = 'Registrasi berhasil. Akun kamu sedang menunggu verifikasi dari admin.';
            $this->redirect('/login');
            return;
        }

        $_SESSION['error'] = 'Registrasi gagal. Silakan coba lagi.';
        $this->redirect('/register');
    }

    /**
     * Menampilkan halaman lupa password (step 1: input username)
     */
    public function forgotPassword()
    {
        if (isset($_SESSION['user'])) {
            $this->redirect('/dashboard');
            return;
        }

        $this->view('auth/forgot_password');
    }

    /**
     * Memproses pencarian username (step 1)
     */
    public function findUser()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/lupa-password');
            return;
        }

        $username = trim($_POST['username'] ?? '');

        if (empty($username)) {
            $_SESSION['error'] = 'Username wajib diisi.';
            $this->redirect('/lupa-password');
            return;
        }

        $user = $this->userModel->findByUsername($username);

        if (!$user) {
            $_SESSION['error'] = 'Username tidak ditemukan.';
            $this->redirect('/lupa-password');
            return;
        }

        if (isset($user['status_akun']) && $user['status_akun'] === 'pending') {
            $_SESSION['error'] = 'Akun kamu belum diverifikasi oleh admin.';
            $this->redirect('/lupa-password');
            return;
        }

        // Simpan id user ke session sementara untuk step 2
        $_SESSION['reset_user_id'] = $user['id'];
        $_SESSION['reset_username'] = $user['username'];

        $this->redirect('/reset-password');
    }

    /**
     * Menampilkan halaman reset password (step 2: input password baru)
     */
    public function showResetPassword()
    {
        if (isset($_SESSION['user'])) {
            $this->redirect('/dashboard');
            return;
        }

        // Harus melalui step 1 dulu
        if (empty($_SESSION['reset_user_id'])) {
            $this->redirect('/lupa-password');
            return;
        }

        $this->view('auth/reset_password', [
            'username' => $_SESSION['reset_username'] ?? ''
        ]);
    }

    /**
     * Memproses reset password (step 2)
     */
    public function doResetPassword()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/lupa-password');
            return;
        }

        if (empty($_SESSION['reset_user_id'])) {
            $_SESSION['error'] = 'Sesi reset password tidak valid. Ulangi dari awal.';
            $this->redirect('/lupa-password');
            return;
        }

        $password        = $_POST['password'] ?? '';
        $passwordConfirm = $_POST['password_confirm'] ?? '';

        if (empty($password) || empty($passwordConfirm)) {
            $_SESSION['error'] = 'Semua field wajib diisi.';
            $this->redirect('/reset-password');
            return;
        }

        if (strlen($password) < 6) {
            $_SESSION['error'] = 'Password minimal 6 karakter.';
            $this->redirect('/reset-password');
            return;
        }

        if ($password !== $passwordConfirm) {
            $_SESSION['error'] = 'Konfirmasi password tidak sesuai.';
            $this->redirect('/reset-password');
            return;
        }

        $userId = $_SESSION['reset_user_id'];
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        $result = $this->userModel->updatePassword($userId, $hashedPassword);

        // Hapus session reset
        unset($_SESSION['reset_user_id'], $_SESSION['reset_username']);

        if ($result) {
            $_SESSION['success'] = 'Password berhasil direset. Silakan login dengan password baru.';
            $this->redirect('/login');
            return;
        }

        $_SESSION['error'] = 'Gagal mereset password. Silakan coba lagi.';
        $this->redirect('/reset-password');
    }

    /**
     * Logout
     */
    public function logout()
    {
        $_SESSION = [];

        if (ini_get('session.use_cookies')) {
            $params = session_get_cookie_params();

            setcookie(
                session_name(),
                '',
                time() - 42000,
                $params['path'],
                $params['domain'],
                $params['secure'],
                $params['httponly']
            );
        }

        session_destroy();

        $this->redirect('/login');
        exit;
    }
}