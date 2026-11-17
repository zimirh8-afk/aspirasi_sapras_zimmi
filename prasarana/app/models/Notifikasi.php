<?php

class Notifikasi extends Model
{
    protected $table = 'notifikasi';

    /**
     * Buat notifikasi baru
     */
    public function create($data)
    {
        $sql = "INSERT INTO {$this->table}
                (user_id, pengaduan_id, judul, pesan, tipe, status, created_at)
                VALUES
                (:user_id, :pengaduan_id, :judul, :pesan, :tipe, 'belum', NOW())";

        $stmt = $this->db->prepare($sql);

        return $stmt->execute([
            ':user_id'       => $data['user_id'],
            ':pengaduan_id'  => $data['pengaduan_id'] ?? null,
            ':judul'         => $data['judul'],
            ':pesan'         => $data['pesan'],
            ':tipe'          => $data['tipe'] ?? 'info'
        ]);
    }

    /**
     * Ambil notifikasi berdasarkan user_id
     */
    public function getByUserId($userId, $limit = 10)
    {
        $limit = (int) $limit;

        $sql = "SELECT n.*, p.judul as judul_pengaduan
                FROM {$this->table} n
                LEFT JOIN aspirasi p ON n.pengaduan_id = p.id
                WHERE n.user_id = :user_id
                ORDER BY n.created_at DESC
                LIMIT {$limit}";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([':user_id' => $userId]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Hitung notifikasi yang belum dibaca
     */
    public function countUnread($userId)
    {
        $sql = "SELECT COUNT(*) as total
                FROM {$this->table}
                WHERE user_id = :user_id AND status = 'belum'";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([':user_id' => $userId]);

        return (int) $stmt->fetchColumn();
    }

    /**
     * Tandai notifikasi sebagai sudah dibaca
     */
    public function markAsRead($id)
    {
        $sql = "UPDATE {$this->table}
                SET status = 'sudah'
                WHERE id = :id";

        $stmt = $this->db->prepare($sql);
        return $stmt->execute([':id' => $id]);
    }

    /**
     * Tandai semua notifikasi sebagai sudah dibaca
     */
    public function markAllAsRead($userId)
    {
        $sql = "UPDATE {$this->table}
                SET status = 'sudah'
                WHERE user_id = :user_id AND status = 'belum'";

        $stmt = $this->db->prepare($sql);
        return $stmt->execute([':user_id' => $userId]);
    }

    /**
     * Kirim notifikasi ke semua admin (untuk pengaduan baru)
     */
    public function notifyAdmins($data)
    {
        // Ambil semua admin
        $userModel = new User();
        $admins = $userModel->getAllAdmins();

        foreach ($admins as $admin) {
            $this->create([
                'user_id'       => $admin['id'],
                'pengaduan_id'  => $data['pengaduan_id'],
                'judul'         => $data['judul'],
                'pesan'         => $data['pesan'],
                'tipe'          => 'pengaduan_baru'
            ]);
        }
    }
}
