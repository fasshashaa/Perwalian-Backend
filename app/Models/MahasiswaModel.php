<?php

namespace App\Models;

use CodeIgniter\Model;

class MahasiswaModel extends Model
{
    protected $table = 'mahasiswa';  
    protected $primaryKey = 'nim';  
    protected $allowedFields = ['nim', 'nama', 'email', 'alamat', 'nidn'];  
    protected $validationRules = [
        'nim' => 'required|numeric|is_unique[mahasiswa.nim]',
        'nama' => 'required|min_length[3]',
        'email' => 'required|valid_email',
        'alamat' => 'required',
        'nidn' => 'required|numeric'
    ];
}
