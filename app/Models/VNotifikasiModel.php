<?php

namespace App\Models;

use CodeIgniter\Model;

class VNotifikasiModel extends Model
{
    protected $table = 'v_notifikasi';  
    protected $primaryKey = 'id';       
    protected $allowedFields = ['nama_mahasiswa', 'nama_dosen', 'tipe', 'tanggal_kirim', 'pesan']; 
    protected $useTimestamps = true;    
}
