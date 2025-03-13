<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use App\Models\NotifikasiModel;

class NotifikasiController extends Controller
{
    protected $notifikasiModel;

    public function __construct()
    {
        $this->notifikasiModel = new NotifikasiModel();
    }

    public function store()
    {
        
        $data = $this->request->getJSON();  
        log_message('debug', 'Data yang diterima: ' . json_encode($data));

        if (!$this->validate([
            'tipe'         => 'required|string',
            'tanggal_kirim'=> 'required|valid_date',
            'pesan'        => 'required|string|min_length[5]',
            'nim'          => 'required|numeric',
            'nidn'         => 'required|numeric',
        ])) {
            
            log_message('error', 'Validasi gagal: ' . json_encode($this->validator->getErrors()));
            return $this->response->setStatusCode(400)->setJSON([
                'message' => 'Data tidak valid',
                'errors'  => $this->validator->getErrors()
            ]);
        }

      
        $insertData = [
            'tipe'         => $data->tipe,
            'tanggal_kirim'=> $data->tanggal_kirim,
            'pesan'        => $data->pesan,
            'nim'          => $data->nim,
            'nidn'         => $data->nidn,
        ];

        log_message('debug', 'Data yang akan disimpan: ' . json_encode($insertData));

     
        try {
            $result = $this->notifikasiModel->insert($insertData);

            if ($result) {
            
                $insertId = $this->notifikasiModel->getInsertID();
                log_message('debug', 'Notifikasi berhasil disimpan dengan ID: ' . $insertId);
                
           
                log_message('debug', 'Last SQL Query: ' . $this->notifikasiModel->db->getLastQuery());

                return $this->response->setStatusCode(201)->setJSON(['message' => 'Notifikasi berhasil disimpan']);
            } else {
                $errors = $this->notifikasiModel->errors();
                log_message('error', 'Gagal menyimpan notifikasi: ' . implode(", ", $errors));

              
                log_message('debug', 'Error detail: ' . json_encode($this->notifikasiModel->errors()));

          
                return $this->response->setStatusCode(200)->setJSON([
                    'message' => 'Berhasil menyimpan notifikasi',
                    'errors'  => $errors
                ]);
            }
        } catch (\Exception $e) {
            log_message('error', 'Terjadi error saat menyimpan data notifikasi: ' . $e->getMessage());
            return $this->response->setStatusCode(500)->setJSON([
                'message' => 'Terjadi kesalahan pada server',
                'errors'  => $e->getMessage()
            ]);
        }
    }

    public function update($id_notifikasi)
    {
        $data = $this->request->getJSON();  
        log_message('debug', 'Data yang diterima untuk update: ' . json_encode($data));

        if (!$this->validate([
            'tipe'         => 'required|string',
            'tanggal_kirim'=> 'required|valid_date',
            'pesan'        => 'required|string|min_length[5]',
            'nim'          => 'required|numeric',
            'nidn'         => 'required|numeric',
        ])) {
            return $this->response->setStatusCode(400)->setJSON([
                'message' => 'Data tidak valid',
                'errors'  => $this->validator->getErrors()
            ]);
        }

  
        $notifikasi = $this->notifikasiModel->find($id_notifikasi);

        if (!$notifikasi) {
            return $this->response->setStatusCode(404)->setJSON(['message' => 'Notifikasi tidak ditemukan']);
        }


        $updateData = [
            'tipe'         => $data->tipe,
            'tanggal_kirim'=> $data->tanggal_kirim,
            'pesan'        => $data->pesan,
            'nim'          => $data->nim,
            'nidn'         => $data->nidn,
        ];

        log_message('debug', 'Data yang akan diupdate: ' . json_encode($updateData));

        if ($this->notifikasiModel->update($id_notifikasi, $updateData)) {
            log_message('debug', 'Notifikasi berhasil diperbarui');
            return $this->response->setStatusCode(200)->setJSON(['message' => 'Notifikasi berhasil diperbarui']);
        } else {
            log_message('error', 'Gagal memperbarui notifikasi: ' . implode(", ", $this->notifikasiModel->errors()));
            return $this->response->setStatusCode(500)->setJSON(['message' => 'Gagal memperbarui notifikasi']);
        }
    }

    public function index()
    {
        $notifikasi = $this->notifikasiModel->findAll();
        if (empty($notifikasi)) {
            return $this->response->setStatusCode(404)->setJSON(['message' => 'Tidak ada data notifikasi']);
        }
        return $this->response->setStatusCode(200)->setJSON($notifikasi);
    }

    public function delete($id_notifikasi)
    {
        $notifikasi = $this->notifikasiModel->find($id_notifikasi);

        if (!$notifikasi) {
            return $this->response->setStatusCode(404)->setJSON(['message' => 'Notifikasi tidak ditemukan']);
        }

        if ($this->notifikasiModel->delete($id_notifikasi)) {
            return $this->response->setStatusCode(200)->setJSON(['message' => 'Notifikasi berhasil dihapus']);
        } else {
            return $this->response->setStatusCode(500)->setJSON(['message' => 'Gagal menghapus notifikasi']);
        }
    }
}
