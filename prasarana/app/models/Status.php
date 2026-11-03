<?php

class Status extends Model
{
    /**
     * Daftar status pengaduan
     */
    public function getAll()
    {
        return [
            [
                'value' => 'Diajukan',
                'label' => 'Diajukan'
            ],
            [
                'value' => 'Diproses',
                'label' => 'Diproses'
            ],
            [
                'value' => 'Selesai',
                'label' => 'Selesai'
            ],
            [
                'value' => 'Ditolak',
                'label' => 'Ditolak'
            ]
        ];
    }

    /**
     * Cek apakah status valid
     */
    public function isValid($status)
    {
        $statuses = array_column(
            $this->getAll(),
            'value'
        );

        return in_array($status, $statuses);
    }

    /**
     * Mengambil progress default berdasarkan status
     */
    public function getDefaultProgress($status)
    {
        $progress = [
            'Diajukan'  => 0,
            'Diproses'  => 50,
            'Selesai'   => 100,
            'Ditolak'   => 0
        ];

        return $progress[$status] ?? 0;
    }
}