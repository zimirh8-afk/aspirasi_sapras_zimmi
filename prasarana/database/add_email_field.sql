-- Tambah kolom email ke tabel users
ALTER TABLE users ADD COLUMN email VARCHAR(255) NULL AFTER username;

-- Tambah index untuk email
CREATE INDEX idx_users_email ON users(email);

-- Note: Jika ingin email wajib, ubah NULL menjadi NOT NULL
