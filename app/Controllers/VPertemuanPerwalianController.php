<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use App\Models\VPertemuanPerwalianModel;

class VPertemuanPerwalianController extends Controller
{
    protected $pertemuanPerwalianModel;

    public function __construct()
    {
        $this->pertemuanPerwalianModel = new VPertemuanPerwalianModel();
    }

    public function store()
    {
        $data = $this->request->getJSON();
        log_message('debug', 'Data yang diterima: ' . json_encode($data));

 
        if (!$this->validate([
            'nama_mahasiswa'  => 'required|min_length[3]',
            'nama_dosen'      => 'required|min_length[3]',
            'tanggal'         => 'required|valid_date',
            'topik'           => 'required|min_length[5]',
            'catatan'         => 'required|min_length[5]',
            'saran_akademik'  => 'required|min_length[5]',
            'bulan_tahun'     => 'required|min_length[7]|max_length[7]' // Format: YYYY-MM
        ])) {
           
            log_message('error', 'Validasi gagal: ' . json_encode($this->validator->getErrors()));
            return $this->response->setStatusCode(400)->setJSON([
                'message' => 'Data tidak valid',
                'errors'  => $this->validator->getErrors()
            ]);
        }

        $insertData = [
            'nama_mahasiswa' => $data->nama_mahasiswa,
            'nama_dosen'     => $data->nama_dosen,
            'tanggal'        => $data->tanggal,
            'topik'          => $data->topik,
            'catatan'        => $data->catatan,
            'saran_akademik' => $data->saran_akademik,
            'bulan_tahun'    => $data->bulan_tahun,
        ];

        log_message('debug', 'Data yang akan disimpan: ' . json_encode($insertData));

        try {
            $result = $this->pertemuanPerwalianModel->save($insertData);

            if ($result) {
      
                $insertId = $this->pertemuanPerwalianModel->getInsertID();
                log_message('debug', 'Pertemuan perwalian berhasil disimpan dengan ID: ' . $insertId);

         
                log_message('debug', 'Last SQL Query: ' . $this->pertemuanPerwalianModel->db->getLastQuery());

                return $this->response->setStatusCode(201)->setJSON(['message' => 'Pertemuan perwalian berhasil disimpan']);
            } else {
              
                $errors = $this->pertemuanPerwalianModel->errors();
                log_message('error', 'Gagal menyimpan pertemuan perwalian: ' . implode(", ", $errors));
                log_message('debug', 'Error detail: ' . json_encode($this->pertemuanPerwalianModel->errors()));

                return $this->response->setStatusCode(500)->setJSON([
                    'message' => 'Gagal menyimpan pertemuan perwalian',
                    'errors'  => $errors
                ]);
            }
        } catch (\Exception $e) {
            log_message('error', 'Terjadi error saat menyimpan data pertemuan perwalian: ' . $e->getMessage());
            return $this->response->setStatusCode(500)->setJSON([
                'message' => 'Terjadi kesalahan pada server',
                'errors'  => $e->getMessage()
            ]);
        }
    }

    public function update($id)
    {
        $data = $this->request->getJSON();
        log_message('debug', 'Data yang diterima untuk update: ' . json_encode($data));

        // Validasi input
        if (!$this->validate([
            'nama_mahasiswa'  => 'required|min_length[3]',
            'nama_dosen'      => 'required|min_length[3]',
            'tanggal'         => 'required|valid_date',
            'topik'           => 'required|min_length[5]',
            'catatan'         => 'required|min_length[5]',
            'saran_akademik'  => 'required|min_length[5]',
            'bulan_tahun'     => 'required|min_length[7]|max_length[7]'
        ])) {
            return $this->response->setStatusCode(400)->setJSON([
                'message' => 'Data tidak valid',
                'errors'  => $this->validator->getErrors()
            ]);
        }

        // Cari pertemuan perwalian berdasarkan ID
        $pertemuan = $this->pertemuanPerwalianModel->find($id);

        if (!$pertemuan) {
            return $this->response->setStatusCode(404)->setJSON(['message' => 'Pertemuan perwalian tidak ditemukan']);
        }

        // Data untuk update
        $updateData = [
            'nama_mahasiswa' => $data->nama_mahasiswa,
            'nama_dosen'     => $data->nama_dosen,
            'tanggal'        => $data->tanggal,
            'topik'          => $data->topik,
            'catatan'        => $data->catatan,
            'saran_akademik' => $data->saran_akademik,
            'bulan_tahun'    => $data->bulan_tahun,
        ];

        log_message('debug', 'Data yang akan diupdate: ' . json_encode($updateData));

        if ($this->pertemuanPerwalianModel->update($id, $updateData)) {
            log_message('debug', 'Pertemuan perwalian berhasil diperbarui');
            return $this->response->setStatusCode(200)->setJSON(['message' => 'Pertemuan perwalian berhasil diperbarui']);
        } else {
            log_message('error', 'Gagal memperbarui pertemuan perwalian: ' . implode(", ", $this->pertemuanPerwalianModel->errors()));
            return $this->response->setStatusCode(500)->setJSON(['message' => 'Gagal memperbarui pertemuan perwalian']);
        }
    }

    public function index()
    {
        $pertemuan = $this->pertemuanPerwalianModel->findAll();
        if (empty($pertemuan)) {
            return $this->response->setStatusCode(404)->setJSON(['message' => 'Tidak ada data pertemuan perwalian']);
        }
        return $this->response->setStatusCode(200)->setJSON($pertemuan);
    }

    public function delete($id)
    {
        $pertemuan = $this->pertemuanPerwalianModel->find($id);

        if (!$pertemuan) {
            return $this->response->setStatusCode(404)->setJSON(['message' => 'Pertemuan perwalian tidak ditemukan']);
        }

        if ($this->pertemuanPerwalianModel->delete($id)) {
            return $this->response->setStatusCode(200)->setJSON(['message' => 'Pertemuan perwalian berhasil dihapus']);
        } else {
            return $this->response->setStatusCode(500)->setJSON(['message' => 'Gagal menghapus pertemuan perwalian']);
        }
    }
}
