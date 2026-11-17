<?php

class User extends Model
{
    protected $table = 'users';

    /**
     * Cari user berdasarkan username
     */
    public function findByUsername($username)
    {
        $sql = "SELECT *
                FROM {$this->table}
                WHERE username = :username
                LIMIT 1";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            ':username' => $username
        ]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Cari user berdasarkan ID
     */
    public function findById($id)
    {
        $sql = "SELECT *
                FROM {$this->table}
                WHERE id = :id
                LIMIT 1";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            ':id' => $id
        ]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Membuat user baru
     */
    public function create($data)
    {
        $sql = "INSERT INTO {$this->table}
                (nama, username, password, role, status_akun, email)
                VALUES
                (:nama, :username, :password, :role, :status_akun, :email)";

        $stmt = $this->db->prepare($sql);

        $stmt->execute([
            ':nama'        => $data['nama'],
            ':username'    => $data['username'],
            ':password'    => $data['password'],
            ':role'        => $data['role'],
            ':status_akun' => $data['status_akun'] ?? 'aktif',
            ':email'       => $data['email'] ?? null
        ]);

        return $this->db->lastInsertId();
    }

    /**
     * Update data profil
     */
    public function update($id, $data)
    {
        // Cek apakah username sudah digunakan user lain
        $sqlCheck = "SELECT id
                     FROM {$this->table}
                     WHERE username = :username
                     AND id != :id
                     LIMIT 1";

        $stmtCheck = $this->db->prepare($sqlCheck);

        $stmtCheck->execute([
            ':username' => $data['username'],
            ':id'       => $id
        ]);

        if ($stmtCheck->fetch()) {
            return false;
        }

        $sql = "UPDATE {$this->table}
                SET nama = :nama,
                    username = :username,
                    updated_at = CURRENT_TIMESTAMP
                WHERE id = :id";

        $params = [
            ':nama'     => $data['nama'],
            ':username' => $data['username'],
            ':id'       => $id
        ];

        if (isset($data['foto'])) {
            $sql = "UPDATE {$this->table}
                    SET nama = :nama,
                        username = :username,
                        foto = :foto,
                        updated_at = CURRENT_TIMESTAMP
                    WHERE id = :id";
            $params[':foto'] = $data['foto'];
        }

        $stmt = $this->db->prepare($sql);

        return $stmt->execute($params);
    }

    /**
     * Update password
     */
    public function updatePassword($id, $password)
    {
        $sql = "UPDATE {$this->table}
                SET password = :password,
                    updated_at = CURRENT_TIMESTAMP
                WHERE id = :id";

        $stmt = $this->db->prepare($sql);

        return $stmt->execute([
            ':password' => $password,
            ':id'       => $id
        ]);
    }

    /**
     * Mengambil semua siswa
     * Digunakan Admin untuk filter pengaduan berdasarkan siswa
     */
    public function getAllSiswa()
    {
        $sql = "SELECT id, nama, username
                FROM {$this->table}
                WHERE role = 'siswa'
                ORDER BY nama ASC";

        $stmt = $this->db->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Mengambil semua admin
     */
    public function getAllAdmin()
    {
        $sql = "SELECT id, nama, username
                FROM {$this->table}
                WHERE role = 'admin'
                ORDER BY nama ASC";

        $stmt = $this->db->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Ambil semua siswa pending (belum diverifikasi)
     */
    public function getPendingSiswa()
    {
        $sql = "SELECT *
                FROM {$this->table}
                WHERE role = 'siswa'
                AND status_akun = 'pending'
                ORDER BY id DESC";

        $stmt = $this->db->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getAllSiswaWithStatus()
    {
        $sql = "SELECT *
                FROM {$this->table}
                WHERE role = 'siswa'
                ORDER BY
                    CASE status_akun
                        WHEN 'pending' THEN 0
                        WHEN 'aktif'   THEN 1
                        ELSE 2
                    END ASC,
                    id DESC";

        $stmt = $this->db->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Hitung siswa pending
     */
    public function countPending()
    {
        $sql = "SELECT COUNT(*) FROM {$this->table}
                WHERE role = 'siswa' AND status_akun = 'pending'";

        $stmt = $this->db->prepare($sql);
        $stmt->execute();

        return (int) $stmt->fetchColumn();
    }

    /**
     * Verifikasi akun siswa
     */
    public function verifikasi($id)
    {
        $sql = "UPDATE {$this->table}
                SET status_akun = 'aktif', updated_at = CURRENT_TIMESTAMP
                WHERE id = :id";

        $stmt = $this->db->prepare($sql);

        return $stmt->execute([':id' => $id]);
    }

    /**
     * Tolak / hapus akun pending
     */
    public function tolak($id)
    {
        $sql = "DELETE FROM {$this->table}
                WHERE id = :id AND status_akun = 'pending'";

        $stmt = $this->db->prepare($sql);

        return $stmt->execute([':id' => $id]);
    }

    /**
     * Ambil semua admin
     */
    public function getAllAdmins()
    {
        $sql = "SELECT * FROM {$this->table} WHERE role = 'admin'";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
