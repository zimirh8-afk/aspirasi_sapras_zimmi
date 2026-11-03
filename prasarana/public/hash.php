<?php
$users = [
    'admin' => 'admin123',
    'budi'  => 'budi123',
    'sari'  => 'sari123',
    'andi'  => 'andi123',
];

echo "<pre>-- Jalankan SQL ini di phpMyAdmin:\n\n";
foreach ($users as $username => $password) {
    $hash = password_hash($password, PASSWORD_DEFAULT);
    echo "UPDATE `users` SET password = '$hash' WHERE username = '$username';\n";
}
echo "</pre>";
