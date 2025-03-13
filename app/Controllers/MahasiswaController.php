<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use App\Models\MahasiswaModel;

class MahasiswaController extends Controller
{
    protected $mahasiswaModel;

    public function __construct()
    {
        $this->mahasiswaModel = new MahasiswaModel();
    }

    public function store()
    {
        
        $data = $this->request->getJSON();  


        log_message('debug', 'Data yang diterima: ' . json_encode($data));

 
        if (!$this->validate([
            'nim'    => 'required|numeric|is_unique[mahasiswa.nim]',  
            'nama'   => 'required|min_length[3]',
            'email'  => 'required|valid_email',
            'alamat' => 'required',
            'nidn'   => 'required|numeric',
        ])) {
           
            log_message('error', 'Validasi gagal: ' . json_encode($this->validator->getErrors()));
            return $this->response->setStatusCode(400)->setJSON([
                'message' => 'Data tidak valid',
                'errors'  => $this->validator->getErrors()
            ]);
        }

        $insertData = [
            'nim'    => $data->nim,
            'nama'   => $data->nama,
            'email'  => $data->email,
            'alamat' => $data->alamat,
            'nidn'   => $data->nidn,
        ];

    
        log_message('debug', 'Data yang akan disimpan: ' . json_encode($insertData));


        try {
            $result = $this->mahasiswaModel->insert($insertData);

            if ($result) {
              
                $insertId = $this->mahasiswaModel->getInsertID();
                log_message('debug', 'Mahasiswa berhasil disimpan dengan ID: ' . $insertId);
                
            
                log_message('debug', 'Last SQL Query: ' . $this->mahasiswaModel->db->getLastQuery());

                return $this->response->setStatusCode(201)->setJSON(['message' => 'Mahasiswa berhasil disimpan']);
            } else {
                
                $errors = $this->mahasiswaModel->errors();
                log_message('error', 'Gagal menyimpan mahasiswa: ' . implode(", ", $errors));

                return $this->response->setStatusCode(200)->setJSON([
                    'message' => 'Berhasil menyimpan mahasiswa',
                  
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

        if (!$this->validate([
            'nama'   => 'required|min_length[3]',
            'email'  => 'required|valid_email',
            'alamat' => 'required|min_length[5]',
            'nidn'   => 'required|numeric'
        ])) {
            return $this->response->setStatusCode(400)->setJSON([
                'message' => 'Data tidak valid',
                'errors'  => $this->validator->getErrors()
            ]);
        }

   
        $mahasiswa = $this->mahasiswaModel->find($nim);

        if (!$mahasiswa) {
            return $this->response->setStatusCode(404)->setJSON(['message' => 'Mahasiswa tidak ditemukan']);
        }


        $updateData = [
            'nama'   => $data->nama,
            'email'  => $data->email,
            'alamat' => $data->alamat,
            'nidn'   => $data->nidn,
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
