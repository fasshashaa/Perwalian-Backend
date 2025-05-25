<?php

namespace App\Models;

use CodeIgniter\Model;

class PertemuanPerwalianModel extends Model
{
    protected $table      = 'pertemuan_perwalian';
    protected $primaryKey = 'id_pertemuan';
    protected $returnType = 'array';
    protected $allowedFields = ['tanggal', 'topik', 'catatan', 'saran_akademik', 'nim', 'nidn'];
    protected $validationRules = [
        'tanggal'         => 'required|valid_date',
        'topik'           => 'required|string',
        'catatan'         => 'required|string',
        'saran_akademik'  => 'required|string',
        'nim'             => 'required|numeric',
        'nidn'            => 'required|numeric',
        'bulan_tahun'     => 'required'
        
    ];

    protected $validationMessages = [
        'tanggal' => [
            'valid_date' => 'Tanggal tidak valid.',
        ],
        'bulan_tahun' => [
            'regex_match' => 'Bulan dan tahun harus dalam format YYYY-MM.',
        ]
    ];

    protected $skipValidation = false;
}
