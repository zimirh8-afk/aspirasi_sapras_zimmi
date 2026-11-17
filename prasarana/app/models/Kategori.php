<?php

class Kategori extends Model
{
    protected $table = 'kategori';

    /**
     * Mengambil semua kategori
     */
    public function getAll()
    {
        $sql = "SELECT *
                FROM {$this->table}
                ORDER BY nama ASC";

        $stmt = $this->db->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Mengambil kategori berdasarkan ID
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
     * Alias untuk findById (agar konsisten dengan method lain)
     */
    public function getById($id)
    {
        return $this->findById($id);
    }

    /**
     * Membuat kategori
     */
    public function create($nama)
    {
        $sql = "INSERT INTO {$this->table}
                (nama)
                VALUES (:nama)";

        $stmt = $this->db->prepare($sql);

        return $stmt->execute([
            ':nama' => $nama
        ]);
    }

    /**
     * Update kategori
     */
    public function update($id, $nama)
    {
        $sql = "UPDATE {$this->table}
                SET nama = :nama
                WHERE id = :id";

        $stmt = $this->db->prepare($sql);

        return $stmt->execute([
            ':nama' => $nama,
            ':id'   => $id
        ]);
    }

    /**
     * Hapus kategori
     */
    public function delete($id)
    {
        $sql = "DELETE FROM {$this->table}
                WHERE id = :id";

        $stmt = $this->db->prepare($sql);

        return $stmt->execute([
            ':id' => $id
        ]);
    }
}