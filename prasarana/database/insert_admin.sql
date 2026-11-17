-- Insert admin user ke hosting
-- Jalankan di phpMyAdmin hosting

INSERT INTO `users` (`id`, `nama`, `username`, `password`, `role`, `foto`, `email`, `status`) 
VALUES (1, 'Zimmi Rezkyana', 'admin', '$2y$10$KYtt1TKp83zp4hmLBbeOsuj7Dh5lYaEBXYEAOm1h/pk.Kho.DJZUG', 'admin', NULL, NULL, 'aktif')
ON DUPLICATE KEY UPDATE 
    `nama` = 'Zimmi Rezkyana',
    `role` = 'admin',
    `status` = 'aktif';
