<?php

class Router
{
    private $routes = [];

    /**
     * Menambahkan route GET
     */
    public function get($path, $action)
    {
        $this->addRoute('GET', $path, $action);
    }

    /**
     * Menambahkan route POST
     */
    public function post($path, $action)
    {
        $this->addRoute('POST', $path, $action);
    }

    /**
     * Menyimpan route
     */
    private function addRoute($method, $path, $action)
    {
        $this->routes[] = [
            'method' => $method,
            'path' => $path,
            'action' => $action
        ];
    }

    /**
     * Menjalankan Router
     */
    public function dispatch()
    {
        $requestMethod = $_SERVER['REQUEST_METHOD'];

        $requestUri = parse_url(
            $_SERVER['REQUEST_URI'],
            PHP_URL_PATH
        );

        /*
        |--------------------------------------------------------------------------
        | Hapus folder project dari URL
        | Dibaca dari config/config.php -> base_path
        |--------------------------------------------------------------------------
        */

        $config = require __DIR__ . '/../config/config.php';
        $basePath = rtrim($config['base_path'] ?? '', '/');

        if (
            $basePath !== '/' &&
            strpos($requestUri, $basePath) === 0
        ) {

            $requestUri = substr(
                $requestUri,
                strlen($basePath)
            );

        }

        if ($requestUri === '') {
            $requestUri = '/';
        }

        /*
        |--------------------------------------------------------------------------
        | Cari Route
        |--------------------------------------------------------------------------
        */

        foreach ($this->routes as $route) {

            if ($route['method'] !== $requestMethod) {
                continue;
            }

            $pattern = $this->convertToRegex(
                $route['path']
            );

            if (preg_match(
                $pattern,
                $requestUri,
                $matches
            )) {

                array_shift($matches);

                return $this->callAction(
                    $route['action'],
                    $matches
                );

            }
        }

        /*
        |--------------------------------------------------------------------------
        | Route Tidak Ditemukan
        |--------------------------------------------------------------------------
        */

        http_response_code(404);

        $baseUrl = defined('BASE_URL') ? BASE_URL : '';
        echo '<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>404 - Halaman Tidak Ditemukan | SIADU</title>
    <link rel="stylesheet" href="' . $baseUrl . '/css/style.css">
    <style>
        body { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); min-height: 100vh; display: flex; align-items: center; justify-content: center; }
        .err-box { background: white; border-radius: 20px; padding: 48px 40px; text-align: center; max-width: 420px; width: 90%; box-shadow: 0 20px 60px rgba(0,0,0,0.2); }
        .err-code { font-size: 80px; font-weight: 800; background: linear-gradient(135deg, #4f46e5, #7c3aed); -webkit-background-clip: text; -webkit-text-fill-color: transparent; line-height: 1; margin-bottom: 8px; }
        .err-title { font-size: 20px; font-weight: 700; color: #111827; margin-bottom: 8px; }
        .err-desc { font-size: 14px; color: #6b7280; margin-bottom: 28px; line-height: 1.6; }
        .err-btn { display: inline-block; background: #4f46e5; color: white; padding: 12px 28px; border-radius: 8px; font-weight: 600; text-decoration: none; font-size: 14px; transition: opacity 0.2s; }
        .err-btn:hover { opacity: 0.9; }
        .err-btn-sec { display: inline-block; color: #4f46e5; padding: 12px 20px; font-weight: 600; text-decoration: none; font-size: 14px; }
    </style>
</head>
<body>
    <div class="err-box">
        <img src="' . $baseUrl . '/logo.png" alt="SIADU" style="height:52px;margin-bottom:24px;object-fit:contain">
        <div class="err-code">404</div>
        <div class="err-title">Halaman Tidak Ditemukan</div>
        <div class="err-desc">Halaman yang kamu cari tidak ada atau sudah dipindahkan. Coba kembali ke dashboard.</div>
        <a href="' . $baseUrl . '/dashboard" class="err-btn">Kembali ke Dashboard</a>
        <br>
        <a href="javascript:history.back()" class="err-btn-sec">← Halaman Sebelumnya</a>
    </div>
</body>
</html>';
    }

    /**
     * Mengubah route menjadi Regular Expression
     */
    private function convertToRegex($path)
    {
        $pattern = preg_replace(
            '/\{([a-zA-Z0-9_]+)\}/',
            '([^/]+)',
            $path
        );

        return '#^' . $pattern . '$#';
    }

    /**
     * Menjalankan Controller
     */
    private function callAction($action, $params = [])
    {
        /*
        |--------------------------------------------------------------------------
        | Jika action berbentuk:
        | Controller@method
        |--------------------------------------------------------------------------
        */

        if (is_string($action)) {

            [$controllerName, $method] =
                explode('@', $action);

        } else {

            return call_user_func_array(
                $action,
                $params
            );

        }

        /*
        |--------------------------------------------------------------------------
        | Load Controller
        |--------------------------------------------------------------------------
        */

        $controllerFile =
            __DIR__ .
            '/../app/controllers/' .
            $controllerName .
            '.php';

        if (!file_exists($controllerFile)) {

            die(
                'Controller tidak ditemukan: ' .
                $controllerName
            );

        }

        require_once $controllerFile;

        /*
        |--------------------------------------------------------------------------
        | Cek Class
        |--------------------------------------------------------------------------
        */

        if (!class_exists($controllerName)) {

            die(
                'Class controller tidak ditemukan: ' .
                $controllerName
            );

        }

        $controller = new $controllerName();

        /*
        |--------------------------------------------------------------------------
        | Cek Method
        |--------------------------------------------------------------------------
        */

        if (!method_exists($controller, $method)) {

            die(
                'Method controller tidak ditemukan: ' .
                $controllerName .
                '@' .
                $method
            );

        }

        /*
        |--------------------------------------------------------------------------
        | Jalankan Method
        |--------------------------------------------------------------------------
        */

        return call_user_func_array(
            [$controller, $method],
            $params
        );
    }
}