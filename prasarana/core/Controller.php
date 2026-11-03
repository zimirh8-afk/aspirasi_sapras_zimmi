<?php

class Controller
{
    /**
     * Memuat View
     */
    protected function view($view, $data = [])
    {
        $viewPath = __DIR__ . '/../app/views/' . $view . '.php';

        if (!file_exists($viewPath)) {

            die(
                'View tidak ditemukan: ' .
                $viewPath
            );

        }

        extract($data);

        require_once $viewPath;
    }

    /**
     * Redirect ke URL tertentu
     */
    protected function redirect($url)
    {
        // Jika URL sudah absolut (http/https), langsung redirect
        if (strpos($url, 'http') === 0) {
            header('Location: ' . $url);
        } else {
            header('Location: ' . BASE_URL . '/' . ltrim($url, '/'));
        }

        exit;
    }

    /**
     * Redirect kembali ke halaman sebelumnya
     */
    protected function back()
    {
        $url = $_SERVER['HTTP_REFERER'] ?? '/';

        header('Location: ' . $url);

        exit;
    }

    /**
     * Mengambil input POST
     */
    protected function post($key, $default = null)
    {
        return $_POST[$key] ?? $default;
    }

    /**
     * Mengambil input GET
     */
    protected function get($key, $default = null)
    {
        return $_GET[$key] ?? $default;
    }

    /**
     * Menyimpan pesan success ke session
     */
    protected function success($message)
    {
        $_SESSION['success'] = $message;
    }

    /**
     * Menyimpan pesan error ke session
     */
    protected function error($message)
    {
        $_SESSION['error'] = $message;
    }

    /**
     * Cek apakah user sudah login
     */
    protected function checkAuth()
    {
        if (!isset($_SESSION['user'])) {
            $this->redirect('/login');
            exit;
        }
    }

    /**
     * Cek role user
     */
    protected function checkRole($role)
    {
        $this->checkAuth();

        if ($_SESSION['user']['role'] !== $role) {
            $this->redirect('/login');
            exit;
        }
    }
}