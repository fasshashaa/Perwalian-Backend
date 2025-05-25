<?php

namespace App\Models;

use CodeIgniter\Model;

class DosenWaliModel extends Model
{
    protected $table = 'dosen_wali';
    protected $primaryKey = 'nidn';
    protected $allowedFields = ['nidn', 'nama', 'email']; 
    protected $useTimestamps = false;

    // Untuk validasi data
    protected $validationRules = [
        'nidn'   => 'required|numeric|is_unique[dosen_wali.nidn]',  
        'nama'   => 'required|min_length[3]',
        'email'  => 'required|valid_email',
    ];

    protected $validationMessages = [
        'nidn' => [
            'required' => 'NIDN harus diisi',
            'numeric' => 'NIDN harus berupa angka',
            'is_unique' => 'NIDN sudah terdaftar',
        ],
        'nama' => [
            'required' => 'Nama harus diisi',
            'min_length' => 'Nama harus memiliki panjang minimal 3 karakter',
        ],
        'email' => [
            'required' => 'Email harus diisi',
            'valid_email' => 'Email tidak valid',
        ]
    ];
}
