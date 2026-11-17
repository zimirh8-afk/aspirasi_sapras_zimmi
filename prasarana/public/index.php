<?php

session_start();

date_default_timezone_set('Asia/Jakarta');

require_once __DIR__ . '/../core/Database.php';
require_once __DIR__ . '/../core/Model.php';
require_once __DIR__ . '/../core/Controller.php';
require_once __DIR__ . '/../core/Router.php';

// Load config dan set global base URL
$config = require __DIR__ . '/../config/config.php';
define('BASE_URL', rtrim($config['base_url'], '/'));

// Helper global untuk generate URL
function url($path = '') {
    return BASE_URL . '/' . ltrim($path, '/');
}

// Autoload models
foreach (glob(__DIR__ . '/../app/models/*.php') as $model) {
    require_once $model;
}

$router = require_once __DIR__ . '/../routes/web.php';

$router->dispatch();