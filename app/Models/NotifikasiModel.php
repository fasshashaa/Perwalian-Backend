<?php

namespace App\Models;

use CodeIgniter\Model;

class NotifikasiModel extends Model
{
    protected $table      = 'notifikasi';
    protected $primaryKey = 'id_notifikasi';
    protected $returnType = 'array';
    protected $allowedFields = ['tipe', 'tanggal_kirim', 'pesan', 'nim', 'nidn'];


    protected $validationRules = [
        'tipe'         => 'required|string',
        'tanggal_kirim'=> 'required|valid_date',
        'pesan'        => 'required|string|min_length[5]',
        'nim'          => 'required|numeric',
        'nidn'         => 'required|numeric',
    ];

    protected $validationMessages = [
        'tipe' => [
            'required' => 'Tipe tidak boleh kosong.',
        ],
        'tanggal_kirim' => [
            'valid_date' => 'Tanggal kirim tidak valid.',
        ],
        'pesan' => [
            'min_length' => 'Pesan harus memiliki minimal 5 karakter.',
        ],
    ];

    protected $skipValidation = false;
}
