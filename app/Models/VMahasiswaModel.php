<?php

namespace App\Models;

use CodeIgniter\Model;

class VMahasiswaModel extends Model
{
    protected $table      = 'v_mahasiswa';  
    protected $primaryKey = 'nim';
    protected $returnType = 'array';
    protected $allowedFields = ['nim', 'nama_mahasiswa', 'email', 'alamat', 'nama_dosen'];
    protected $validationRules = [
        'nim'           => 'required|numeric|is_unique[v_mahasiswa.nim]',
        'nama_mahasiswa'=> 'required|min_length[3]',
        'email'         => 'required|valid_email',
        'alamat'        => 'required',
        'nama_dosen'    => 'required|min_length[3]',
    ];

    protected $validationMessages = [
        'nim' => [
            'is_unique' => 'NIM sudah terdaftar.',
        ]
    ];

    protected $skipValidation = false;
}
