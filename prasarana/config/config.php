<?php

return [

    'app_name'        => 'Prasarana Sekolah',
    'app_description' => 'Sistem Pengaduan Sarana Prasarana Sekolah',

    /*
    |--------------------------------------------------------------------------
    | Base URL & Base Path — Auto Detect
    |--------------------------------------------------------------------------
    | Otomatis mendeteksi environment:
    | - localhost -> pakai config lokal
    | - hosting   -> pakai config production
    |--------------------------------------------------------------------------
    */
    'base_url'  => (isset($_SERVER['HTTP_HOST']) && strpos($_SERVER['HTTP_HOST'], 'localhost') !== false)
                    ? 'http://localhost/prasarana'
                    : 'https://prasana.infinityfree.me',

    'base_path' => (isset($_SERVER['HTTP_HOST']) && strpos($_SERVER['HTTP_HOST'], 'localhost') !== false)
                    ? '/prasarana'
                    : '',

    'timezone' => 'Asia/Jakarta',

];
