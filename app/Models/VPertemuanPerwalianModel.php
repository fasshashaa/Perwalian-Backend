<?php

namespace App\Models;

use CodeIgniter\Model;

class VPertemuanPerwalianModel extends Model
{
    protected $table = 'v_pertemuan_perwalian'; // Nama tabel yang digunakan
    protected $primaryKey = 'id';               // Pastikan primary key sesuai
    protected $allowedFields = [
        'nama_mahasiswa', 'nama_dosen', 'tanggal', 'topik', 'catatan', 'saran_akademik', 'bulan_tahun'
    ]; // Kolom yang bisa diinsert atau update
    protected $useTimestamps = true;            // Jika Anda menggunakan timestamp, aktifkan ini
}
