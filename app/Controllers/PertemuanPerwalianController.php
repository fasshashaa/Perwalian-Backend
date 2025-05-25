<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use App\Models\PertemuanPerwalianModel;

class PertemuanPerwalianController extends Controller
{
    protected $pertemuanPerwalianModel;

    public function __construct()
    {
        $this->pertemuanPerwalianModel = new PertemuanPerwalianModel();
    }

    public function store()
    {
        
        $data = $this->request->getJSON();
        $this->request->setGlobal('post', (array) $data);

        if (!$this->validate([
            'tanggal'        => 'required|valid_date',
            'topik'          => 'required|string',
            'catatan'        => 'required|string',
            'nim'            => 'required|numeric',
            'nidn'           => 'required|numeric',
            'bulan_tahun'    => 'required|string',
            'saran_akademik' => 'permit_empty|string',            


        ])) {
            return $this->response->setStatusCode(400)->setJSON([
                'message' => 'Validasi gagal',
                'errors'  => $this->validator->getErrors()
            ]);
        }

        $insertData = [
            'tanggal'        => $data->tanggal,
            'topik'          => $data->topik,
            'catatan'        => $data->catatan,
            'nim'            => $data->nim,
            'nidn'           => $data->nidn,
            'bulan_tahun'    => $data->bulan_tahun,
            'saran_akademik' => $data->saran_akademik ?? 'kosong'
        ];

        if ($this->pertemuanPerwalianModel->insert($insertData)) {
            return $this->response->setStatusCode(201)->setJSON([
                'message' => 'Berhasil disimpan'
            ]);
        } else {
            
            return $this->response->setStatusCode(400)->setJSON([
                'message' => 'Gagal menyimpan',
                'errors'  => $this->pertemuanPerwalianModel->errors()
            ]);
        }
    }


    public function update($id_pertemuan)
    {
        $data = $this->request->getJSON();  
        log_message('debug', 'Data yang diterima untuk update: ' . json_encode($data));

       
        if (!$this->validate([
            'tanggal'         => 'required|valid_date',
            'topik'           => 'required|string',
            'catatan'         => 'required|string',
            'saran_akademik'  => 'permit_empty|string',
            'nim'             => 'required|numeric',
            'nidn'            => 'required|numeric',
          
        ])) {
            return $this->response->setStatusCode(400)->setJSON([
                'message' => 'Data tidak valid',
                'errors'  => $this->validator->getErrors()
            ]);
        }

   
        $pertemuan = $this->pertemuanPerwalianModel->find($id_pertemuan);

        if (!$pertemuan) {
            return $this->response->setStatusCode(404)->setJSON(['message' => 'Pertemuan perwalian tidak ditemukan']);
        }

        $updateData = [
            'tanggal'         => $data->tanggal,
            'topik'           => $data->topik,
            'catatan'         => $data->catatan,
            'saran_akademik'  => $data->saran_akademik ?? "kosong",
            'nim'             => $data->nim,
            'nidn'            => $data->nidn,
    
        ];

        log_message('debug', 'Data yang akan diupdate: ' . json_encode($updateData));

        if ($this->pertemuanPerwalianModel->update($id_pertemuan, $updateData)) {
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

    public function delete($id_pertemuan)
    {
        $pertemuan = $this->pertemuanPerwalianModel->find($id_pertemuan);

        if (!$pertemuan) {
            return $this->response->setStatusCode(404)->setJSON(['message' => 'Pertemuan perwalian tidak ditemukan']);
        }

        if ($this->pertemuanPerwalianModel->delete($id_pertemuan)) {
            return $this->response->setStatusCode(200)->setJSON(['message' => 'Pertemuan perwalian berhasil dihapus']);
        } else {
            return $this->response->setStatusCode(500)->setJSON(['message' => 'Gagal menghapus pertemuan perwalian']);
        }
    }
}
