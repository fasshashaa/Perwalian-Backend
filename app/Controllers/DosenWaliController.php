<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use App\Models\DosenWaliModel;

class DosenWaliController extends Controller
{
    protected $dosenWaliModel;

    public function __construct()
    {
        $this->dosenWaliModel = new DosenWaliModel();
    }

    public function store()
    {
    
        $data = $this->request->getJSON();  

        log_message('debug', 'Data yang diterima: ' . json_encode($data));

        if (!$this->validate($this->dosenWaliModel->validationRules)) {
            log_message('error', 'Validasi gagal: ' . json_encode($this->validator->getErrors()));
            return $this->response->setStatusCode(400)->setJSON([
                'message' => 'Data tidak valid',
                'errors'  => $this->validator->getErrors()
            ]);
        }

        $insertData = [
            'nidn'   => $data->nidn,
            'nama'   => $data->nama,
            'email'  => $data->email,
        ];

      
        log_message('debug', 'Data yang akan disimpan: ' . json_encode($insertData));

        try {
            $result = $this->dosenWaliModel->insert($insertData);

            if ($result) {
                $insertId = $this->dosenWaliModel->getInsertID();
                log_message('debug', 'Dosen Wali berhasil disimpan dengan ID: ' . $insertId);

                return $this->response->setStatusCode(201)->setJSON(['message' => 'Dosen Wali berhasil disimpan']);
            } else {
              
                $errors = $this->dosenWaliModel->errors();
                log_message('error', 'Gagal menyimpan dosen wali: ' . implode(", ", $errors));

                return $this->response->setStatusCode(200)->setJSON([
                    'message' => 'Berhasil menyimpan dosen wali',
                   
                ]);
            }
        } catch (\Exception $e) {
            log_message('error', 'Terjadi error saat menyimpan data dosen wali: ' . $e->getMessage());
            return $this->response->setStatusCode(500)->setJSON([
                'message' => 'Terjadi kesalahan pada server',
                'errors'  => $e->getMessage()
            ]);
        }
    }

    public function update($nidn)
    {
        $data = $this->request->getJSON();  
        log_message('debug', 'Data yang diterima untuk update: ' . json_encode($data));

 
        if (!$this->validate([
            'nama'   => 'required|min_length[3]',
            'email'  => 'required|valid_email',
        ])) {
            return $this->response->setStatusCode(400)->setJSON([
                'message' => 'Data tidak valid',
                'errors'  => $this->validator->getErrors()
            ]);
        }


        $dosenWali = $this->dosenWaliModel->find($nidn);

        if (!$dosenWali) {
            return $this->response->setStatusCode(404)->setJSON(['message' => 'Dosen Wali tidak ditemukan']);
        }

        $updateData = [
            'nama'   => $data->nama,
            'email'  => $data->email,
        ];

        log_message('debug', 'Data yang akan diupdate: ' . json_encode($updateData));

        if ($this->dosenWaliModel->update($nidn, $updateData)) {
            log_message('debug', 'Dosen Wali berhasil diperbarui');
            return $this->response->setStatusCode(200)->setJSON(['message' => 'Dosen Wali berhasil diperbarui']);
        } else {
            log_message('error', 'Gagal memperbarui dosen wali: ' . implode(", ", $this->dosenWaliModel->errors()));
            return $this->response->setStatusCode(500)->setJSON(['message' => 'Gagal memperbarui dosen wali']);
        }
    }

    public function index()
    {
        $dosenWali = $this->dosenWaliModel->findAll();
        if (empty($dosenWali)) {
            return $this->response->setStatusCode(404)->setJSON(['message' => 'Tidak ada data dosen wali']);
        }
        return $this->response->setStatusCode(200)->setJSON($dosenWali);
    }

    public function delete($nidn)
    {
        $dosenWali = $this->dosenWaliModel->find($nidn);

        if (!$dosenWali) {
            return $this->response->setStatusCode(404)->setJSON(['message' => 'Dosen Wali tidak ditemukan']);
        }

        if ($this->dosenWaliModel->delete($nidn)) {
            return $this->response->setStatusCode(200)->setJSON(['message' => 'Dosen Wali berhasil dihapus']);
        } else {
            return $this->response->setStatusCode(500)->setJSON(['message' => 'Gagal menghapus dosen wali']);
        }
    }
}
