<?php

require_once __DIR__ . '/../core/Router.php';

$router = new Router();


// Redirect dashboard utama berdasarkan role
$router->get(
    '/dashboard',
    'DashboardController@index'
);


// Halaman Login
$router->get(
    '/',
    'AuthController@login'
);

$router->get(
    '/login',
    'AuthController@login'
);


// Proses Login
$router->post(
    '/login',
    'AuthController@authenticate'
);


// Logout
$router->get(
    '/logout',
    'AuthController@logout'
);


// Dashboard Siswa
$router->get(
    '/siswa/dashboard',
    'DashboardController@siswa'
);


// Dashboard Admin
$router->get(
    '/admin/dashboard',
    'DashboardController@admin'
);


// Halaman daftar pengaduan siswa
$router->get(
    '/siswa/pengaduan',
    'PengaduanController@index'
);


// Halaman form pengaduan
$router->get(
    '/siswa/pengaduan/create',
    'PengaduanController@create'
);


// Proses membuat pengaduan
$router->post(
    '/siswa/pengaduan/store',
    'PengaduanController@store'
);


// Detail pengaduan siswa
$router->get(
    '/siswa/pengaduan/detail/{id}',
    'PengaduanController@detail'
);


// Daftar seluruh pengaduan admin
$router->get(
    '/admin/pengaduan',
    'PengaduanController@index'
);


// Detail pengaduan admin
$router->get(
    '/admin/pengaduan/detail/{id}',
    'PengaduanController@detail'
);


// Halaman update pengaduan
$router->get(
    '/admin/pengaduan/edit/{id}',
    'PengaduanController@edit'
);


// Proses update pengaduan
$router->post(
    '/admin/pengaduan/update/{id}',
    'PengaduanController@updateStatus'
);


// Feedback siswa
$router->get(
    '/siswa/feedback',
    'FeedbackController@index'
);


// Feedback admin
$router->get(
    '/admin/feedback',
    'FeedbackController@index'
);


// Simpan feedback (admin)
$router->post(
    '/admin/feedback/store',
    'FeedbackController@store'
);


// Histori siswa
$router->get(
    '/siswa/histori',
    'PengaduanController@historiSiswa'
);


// Histori admin
$router->get(
    '/admin/histori',
    'PengaduanController@historiAdmin'
);


// Profile
$router->get(
    '/profile',
    'ProfileController@index'
);

$router->get(
    '/admin/profile',
    'ProfileController@index'
);

$router->get(
    '/siswa/profile',
    'ProfileController@index'
);

$router->post(
    '/profile/update',
    'ProfileController@update'
);

$router->post(
    '/profile/password',
    'ProfileController@updatePassword'
);

$router->post(
    '/profile/update-password',
    'ProfileController@updatePassword'
);


// Register
$router->get(
    '/register',
    'AuthController@register'
);

$router->post(
    '/register',
    'AuthController@store'
);

// Lupa Password - step 1: input username
$router->get(
    '/lupa-password',
    'AuthController@forgotPassword'
);

$router->post(
    '/lupa-password/cari',
    'AuthController@findUser'
);

// Reset Password - step 2: input password baru
$router->get(
    '/reset-password',
    'AuthController@showResetPassword'
);

$router->post(
    '/reset-password',
    'AuthController@doResetPassword'
);

// Verifikasi akun siswa (admin)
$router->get(
    '/admin/verifikasi',
    'VerifikasiController@index'
);

$router->get(
    '/admin/verifikasi/approve/{id}',
    'VerifikasiController@approve'
);

$router->get(
    '/admin/verifikasi/tolak/{id}',
    'VerifikasiController@tolak'
);

// Edit pengaduan siswa
$router->get(
    '/siswa/pengaduan/edit/{id}',
    'PengaduanController@editSiswa'
);

$router->post(
    '/siswa/pengaduan/edit/{id}',
    'PengaduanController@updateSiswa'
);

// Hapus pengaduan siswa
$router->get(
    '/siswa/pengaduan/delete/{id}',
    'PengaduanController@deleteSiswa'
);

// API Routes for Notifications
$router->get('/api/notifikasi', 'ApiController@notifikasi');
$router->post('/api/notifikasi/read/{id}', 'ApiController@readNotif');
$router->post('/api/notifikasi/mark-all', 'ApiController@markAllRead');

return $router;
