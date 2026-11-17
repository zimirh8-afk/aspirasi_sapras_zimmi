<?php

class Siswa extends Model
{
    protected $table = 'siswa';

    public function findByUserId($userId)
    {
        $sql = "SELECT * FROM {$this->table} WHERE id_user = :id_user LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id_user' => $userId]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function findByIdSiswa($idSiswa)
    {
        $sql = "SELECT * FROM {$this->table} WHERE id_siswa = :id_siswa LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id_siswa' => $idSiswa]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function findByNis($nis)
    {
        $sql = "SELECT * FROM {$this->table} WHERE nis = :nis LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':nis' => $nis]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function create($data)
    {
        $sql = "INSERT INTO {$this->table} (id_user, nis, nama_siswa, kelas)
                VALUES (:id_user, :nis, :nama_siswa, :kelas)";

        $stmt = $this->db->prepare($sql);

        $nis   = (!empty($data['nis'])) ? $data['nis'] : 'TEMP-' . $data['id_user'];
        $kelas = (!empty($data['kelas'])) ? $data['kelas'] : '-';

        return $stmt->execute([
            ':id_user'    => $data['id_user'],
            ':nis'        => $nis,
            ':nama_siswa' => $data['nama_siswa'],
            ':kelas'      => $kelas
        ]);
    }
}
