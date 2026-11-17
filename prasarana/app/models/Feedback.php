<?php

class Feedback extends Model
{
    protected $table = 'feedback';

    /**
     * Membuat feedback
     */
    public function create($data)
    {
        $sql = "INSERT INTO {$this->table}
                (
                    pengaduan_id,
                    admin_id,
                    isi
                )
                VALUES
                (
                    :pengaduan_id,
                    :admin_id,
                    :isi
                )";

        $stmt = $this->db->prepare($sql);

        return $stmt->execute([
            ':pengaduan_id' => $data['pengaduan_id'],
            ':admin_id'     => $data['admin_id'],
            ':isi'          => $data['isi']
        ]);
    }

    /**
     * Semua feedback
     */
    public function getAll()
    {
        $sql = "SELECT
                    f.*,
                    p.judul AS judul_pengaduan,
                    u.nama AS nama_admin,
                    s.nama AS nama_siswa

                FROM {$this->table} f

                LEFT JOIN aspirasi p
                    ON f.pengaduan_id = p.id

                LEFT JOIN users u
                    ON f.admin_id = u.id

                LEFT JOIN siswa sw
                    ON p.user_id = sw.id_siswa

                LEFT JOIN users s
                    ON sw.id_user = s.id

                ORDER BY f.created_at DESC";

        $stmt = $this->db->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Feedback berdasarkan siswa
     */
    public function getByUser($userId)
    {
        $sql = "SELECT
                    f.*,
                    p.judul AS judul_pengaduan,
                    p.status,
                    p.progress,
                    u.nama AS nama_admin

                FROM {$this->table} f

                LEFT JOIN aspirasi p
                    ON f.pengaduan_id = p.id

                LEFT JOIN siswa sw
                    ON p.user_id = sw.id_siswa

                LEFT JOIN users u
                    ON f.admin_id = u.id

                WHERE sw.id_user = :user_id

                ORDER BY f.created_at DESC";

        $stmt = $this->db->prepare($sql);

        $stmt->execute([
            ':user_id' => $userId
        ]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Feedback berdasarkan pengaduan
     */
    public function getByPengaduan($pengaduanId)
    {
        $sql = "SELECT
                    f.*,
                    u.nama AS nama_admin

                FROM {$this->table} f

                LEFT JOIN users u
                    ON f.admin_id = u.id

                WHERE f.pengaduan_id = :pengaduan_id

                ORDER BY f.created_at DESC";

        $stmt = $this->db->prepare($sql);

        $stmt->execute([
            ':pengaduan_id' => $pengaduanId
        ]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Mengambil feedback terbaru untuk sebuah pengaduan
     */
    public function getLatestByPengaduan($pengaduanId)
    {
        $sql = "SELECT
                    f.*,
                    u.nama AS nama_admin

                FROM {$this->table} f

                LEFT JOIN users u
                    ON f.admin_id = u.id

                WHERE f.pengaduan_id = :pengaduan_id

                ORDER BY f.created_at DESC

                LIMIT 1";

        $stmt = $this->db->prepare($sql);

        $stmt->execute([
            ':pengaduan_id' => $pengaduanId
        ]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}