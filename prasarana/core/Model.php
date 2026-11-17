<?php

require_once __DIR__ . '/Database.php';

class Model
{
    protected $db;

    protected $table;

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }

    /**
     * Mengambil semua data
     */
    public function all()
    {
        $query = "SELECT * FROM {$this->table}";

        $stmt = $this->db->prepare($query);

        $stmt->execute();

        return $stmt->fetchAll();
    }

    /**
     * Mengambil data berdasarkan ID
     */
    public function find($id)
    {
        $query = "SELECT * FROM {$this->table} WHERE id = :id LIMIT 1";

        $stmt = $this->db->prepare($query);

        $stmt->execute([
            'id' => $id
        ]);

        return $stmt->fetch();
    }

    /**
     * Menghapus data berdasarkan ID
     */
    public function delete($id)
    {
        $query = "DELETE FROM {$this->table} WHERE id = :id";

        $stmt = $this->db->prepare($query);

        return $stmt->execute([
            'id' => $id
        ]);
    }
}