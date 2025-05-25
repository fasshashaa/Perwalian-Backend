<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use App\Models\UserModel;
use CodeIgniter\I18n\Time;

class AuthController extends Controller
{
    protected $userModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
    }


    public function register()
    {
        $data = $this->request->getJSON();
        log_message('debug', 'Data yang diterima untuk registrasi: ' . json_encode($data));
        if (!$this->validate([
            'username' => 'required|min_length[3]|is_unique[users.username]',
            'email'    => 'required|valid_email|is_unique[users.email]',
            'password' => 'required|min_length[6]',
            'role'     => 'required|in_list[mahasiswa,dosen wali]'
        ])) {

            log_message('error', 'Validasi gagal: ' . json_encode($this->validator->getErrors()));
            return $this->response->setStatusCode(400)->setJSON([
                'message' => 'Data tidak valid',
                'errors'  => $this->validator->getErrors()
            ]);
        }
        $insertData = [
            'username' => $data->username,
            'email'    => $data->email,
            'password' => $data->password,
            'role'     => $data->role,
        ];


        log_message('debug', 'Data yang akan disimpan: ' . json_encode($insertData));


        try {
            $result = $this->userModel->save($insertData);

            if ($result) {

                $insertId = $this->userModel->getInsertID();
                log_message('debug', 'User berhasil disimpan dengan ID: ' . $insertId);

                return $this->response->setStatusCode(201)->setJSON(['message' => 'Registrasi berhasil']);
            } else {

                $errors = $this->userModel->errors();
                log_message('error', 'Gagal menyimpan user: ' . implode(", ", $errors));

                return $this->response->setStatusCode(500)->setJSON([
                    'message' => 'Gagal menyimpan user',
                    'errors'  => $errors
                ]);
            }
        } catch (\Exception $e) {
            log_message('error', 'Terjadi error saat menyimpan data user: ' . $e->getMessage());
            return $this->response->setStatusCode(500)->setJSON([
                'message' => 'Terjadi kesalahan pada server',
                'errors'  => $e->getMessage()
            ]);
        }
    }

    public function login()
    {
        $data = $this->request->getJSON();

        // Validasi dasar manual
        if (!isset($data->email) || !isset($data->password)) {
            return $this->response->setStatusCode(400)->setJSON([
                'message' => 'Email dan password harus diisi.'
            ]);
        }

        // Query dengan JOIN untuk dapet data lengkap sekaligus
        $db = \Config\Database::connect();
        $query = "
        SELECT 
            u.*,
            m.nama as nama_mahasiswa, 
            m.alamat as alamat_mahasiswa,
            m.nidn as dosen_wali_nim,
            d.nama as nama_dosen
        FROM users u
        LEFT JOIN mahasiswa m ON u.nim = m.nim
        LEFT JOIN dosen_wali d ON u.nidn = d.nidn
        WHERE u.email = ?
    ";

        $user = $db->query($query, [$data->email])->getRowArray();

        if (!$user) {
            return $this->response->setStatusCode(404)->setJSON([
                'message' => 'Email tidak ditemukan.'
            ]);
        }

        // Cek password
        if ($data->password !== $user['password']) {
            return $this->response->setStatusCode(401)->setJSON([
                'message' => 'Password salah.'
            ]);
        }

        // Susun response data
        $userData = [
            'id_user'  => $user['id_user'],
            'username' => $user['username'],
            'email'    => $user['email'],
            'role'     => $user['role'],
        ];

        // Tambah data spesifik berdasarkan role
        if ($user['role'] === 'mahasiswa' && !empty($user['nama_mahasiswa'])) {
            $userData['mahasiswa'] = [
                'nim' => $user['nim'],
                'nama' => $user['nama_mahasiswa'],
                'alamat' => $user['alamat_mahasiswa'],
                'nidn' => $user['dosen_wali_nim']
            ];
        } elseif ($user['role'] === 'dosen wali' && !empty($user['nama_dosen'])) {
            $userData['dosen'] = [
                'nidn' => $user['nidn'],
                'nama' => $user['nama_dosen']
            ];
        }

        return $this->response->setStatusCode(200)->setJSON([
            'message' => 'Login berhasil.',
            'user' => $userData
        ]);
    }
}
