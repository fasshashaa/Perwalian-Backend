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

    log_message('debug', 'Data yang diterima untuk login: ' . json_encode($data));

    
    if (!$this->validate([
        'email'    => 'required|valid_email',
        'password' => 'required'
    ])) {
    
        log_message('error', 'Validasi gagal: ' . json_encode($this->validator->getErrors()));
        return $this->response->setStatusCode(400)->setJSON([
            'message' => 'Data tidak valid',
            'errors'  => $this->validator->getErrors()
        ]);
    }

    $user = $this->userModel->where('email', $data->email)->first();

    if (!$user) {
        return $this->response->setStatusCode(404)->setJSON(['message' => 'Email tidak ditemukan']);
    }

    if ($data->password === $user['password']) {
        log_message('debug', 'User berhasil login');

        return $this->response->setStatusCode(200)->setJSON([
            'message' => 'Login berhasil',
            'user'    => [
                'id_user' => $user['id_user'],
                'username' => $user['username'],
                'email'    => $user['email'],
                'role'     => $user['role'],
            ]
        ]);
    } else {
        return $this->response->setStatusCode(401)->setJSON(['message' => 'Password salah']);
    }
}

}
