<?php

class HistoriPengaduan extends Model
{
    protected $table = 'histori_pengaduan';

    /**
     * Membuat histori baru
     */
    public function create($data)
    {
        $sql = "INSERT INTO {$this->table}
                (
                    pengaduan_id,
                    status,
                    progress,
                    keterangan
                )
                VALUES
                (
                    :pengaduan_id,
                    :status,
                    :progress,
                    :keterangan
                )";

        $stmt = $this->db->prepare($sql);

        return $stmt->execute([
            ':pengaduan_id' => $data['pengaduan_id'],
            ':status'       => $data['status'],
            ':progress'     => $data['progress'],
            ':keterangan'   => $data['keterangan']
        ]);
    }

    /**
     * Mengambil histori berdasarkan pengaduan
     */
    public function getByPengaduan($pengaduanId)
    {
        $sql = "SELECT *
                FROM {$this->table}

                WHERE pengaduan_id = :pengaduan_id

                ORDER BY created_at ASC";

        $stmt = $this->db->prepare($sql);

        $stmt->execute([
            ':pengaduan_id' => $pengaduanId
        ]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Mengambil histori terbaru
     */
    public function getLatestByPengaduan($pengaduanId)
    {
        $sql = "SELECT *
                FROM {$this->table}

                WHERE pengaduan_id = :pengaduan_id

                ORDER BY created_at DESC

                LIMIT 1";

        $stmt = $this->db->prepare($sql);

        $stmt->execute([
            ':pengaduan_id' => $pengaduanId
        ]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Menghapus histori berdasarkan pengaduan
     */
    public function deleteByPengaduan($pengaduanId)
    {
        $sql = "DELETE FROM {$this->table}
                WHERE pengaduan_id = :pengaduan_id";

        $stmt = $this->db->prepare($sql);

        return $stmt->execute([
            ':pengaduan_id' => $pengaduanId
        ]);
    }
}