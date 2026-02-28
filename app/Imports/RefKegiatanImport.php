<?php

namespace App\Imports;

use App\Models\RefKegiatan;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithUpserts; // Tambahkan ini

class RefKegiatanImport implements ToModel, WithHeadingRow, WithUpserts
{
    public function model(array $row)
    {
        return new RefKegiatan([
            'nama_jenis'    => $row['nama_jenis'],
            'nama_kegiatan' => $row['nama_kegiatan'],
            'output'        => $row['output'],
            'aktif'         => $row['aktif'] ?? 'ya',
        ]);
    }

    /**
     * Tentukan kolom mana yang jadi patokan unik (misal: nama_kegiatan)
     */
    public function uniqueBy()
    {
        return 'nama_kegiatan';
    }
}