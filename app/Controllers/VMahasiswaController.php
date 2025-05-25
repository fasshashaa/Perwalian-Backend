<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use App\Models\VMahasiswaModel;

class VMahasiswaController extends Controller
{
    protected $mahasiswaModel;

    public function __construct()
    {
        $this->mahasiswaModel = new VMahasiswaModel();
    }

    public function store()
    {
       
        $data = $this->request->getJSON();  

      
        log_message('debug', 'Data yang diterima: ' . json_encode($data));

        if (!$this->validate([
            'nim'           => 'required|numeric|is_unique[v_mahasiswa.nim]',  
            'nama_mahasiswa'=> 'required|min_length[3]',
            'email'         => 'required|valid_email',
            'alamat'        => 'required',
            'nama_dosen'    => 'required|min_length[3]',
        ])) {
          
            log_message('error', 'Validasi gagal: ' . json_encode($this->validator->getErrors()));
            return $this->response->setStatusCode(400)->setJSON([
                'message' => 'Data tidak valid',
                'errors'  => $this->validator->getErrors()
            ]);
        }

        $insertData = [
            'nim'           => $data->nim,
            'nama_mahasiswa'=> $data->nama_mahasiswa,
            'email'         => $data->email,
            'alamat'        => $data->alamat,
            'nama_dosen'    => $data->nama_dosen,
        ];

        log_message('debug', 'Data yang akan disimpan: ' . json_encode($insertData));

        try {
            $result = $this->mahasiswaModel->save($insertData);

            if ($result) {
                
                $insertId = $this->mahasiswaModel->getInsertID();
                log_message('debug', 'Mahasiswa berhasil disimpan dengan ID: ' . $insertId);
                
      
                log_message('debug', 'Last SQL Query: ' . $this->mahasiswaModel->db->getLastQuery());

                return $this->response->setStatusCode(201)->setJSON(['message' => 'Mahasiswa berhasil disimpan']);
            } else {
              
                $errors = $this->mahasiswaModel->errors();
                log_message('error', 'Gagal menyimpan mahasiswa: ' . implode(", ", $errors));

                log_message('debug', 'Error detail: ' . json_encode($this->mahasiswaModel->errors()));

                return $this->response->setStatusCode(500)->setJSON([
                    'message' => 'Gagal menyimpan mahasiswa',
                    'errors'  => $errors
                ]);
            }
        } catch (\Exception $e) {
            log_message('error', 'Terjadi error saat menyimpan data mahasiswa: ' . $e->getMessage());
            return $this->response->setStatusCode(500)->setJSON([
                'message' => 'Terjadi kesalahan pada server',
                'errors'  => $e->getMessage()
            ]);
        }
    }

    public function update($nim)
    {
        $data = $this->request->getJSON();  
        log_message('debug', 'Data yang diterima untuk update: ' . json_encode($data));

        // Validasi input
        if (!$this->validate([
            'nama_mahasiswa'=> 'required|min_length[3]',
            'email'         => 'required|valid_email',
            'alamat'        => 'required|min_length[5]',
            'nama_dosen'    => 'required|min_length[3]',
        ])) {
            return $this->response->setStatusCode(400)->setJSON([
                'message' => 'Data tidak valid',
                'errors'  => $this->validator->getErrors()
            ]);
        }

        // Cari mahasiswa berdasarkan NIM
        $mahasiswa = $this->mahasiswaModel->find($nim);

        if (!$mahasiswa) {
            return $this->response->setStatusCode(404)->setJSON(['message' => 'Mahasiswa tidak ditemukan']);
        }

        // Data untuk update
        $updateData = [
            'nama_mahasiswa'=> $data->nama_mahasiswa,
            'email'         => $data->email,
            'alamat'        => $data->alamat,
            'nama_dosen'    => $data->nama_dosen,
        ];

        log_message('debug', 'Data yang akan diupdate: ' . json_encode($updateData));

        if ($this->mahasiswaModel->update($nim, $updateData)) {
            log_message('debug', 'Mahasiswa berhasil diperbarui');
            return $this->response->setStatusCode(200)->setJSON(['message' => 'Mahasiswa berhasil diperbarui']);
        } else {
            log_message('error', 'Gagal memperbarui mahasiswa: ' . implode(", ", $this->mahasiswaModel->errors()));
            return $this->response->setStatusCode(500)->setJSON(['message' => 'Gagal memperbarui mahasiswa']);
        }
    }

    public function index()
    {
        $mahasiswa = $this->mahasiswaModel->findAll();
        if (empty($mahasiswa)) {
            return $this->response->setStatusCode(404)->setJSON(['message' => 'Tidak ada data mahasiswa']);
        }
        return $this->response->setStatusCode(200)->setJSON($mahasiswa);
    }

    public function delete($nim)
    {
        $mahasiswa = $this->mahasiswaModel->find($nim);

        if (!$mahasiswa) {
            return $this->response->setStatusCode(404)->setJSON(['message' => 'Mahasiswa tidak ditemukan']);
        }

        if ($this->mahasiswaModel->delete($nim)) {
            return $this->response->setStatusCode(200)->setJSON(['message' => 'Mahasiswa berhasil dihapus']);
        } else {
            return $this->response->setStatusCode(500)->setJSON(['message' => 'Gagal menghapus mahasiswa']);
        }
    }
}
