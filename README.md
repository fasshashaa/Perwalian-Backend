# 🎓 Sistem Perwalian Mahasiswa - Backend

---
## 🧰 Tools yang dibutuhkan ( Backend ) 
1. PHP 8.3.17 ( atau versi lebih tinggi )
2. Visual Studio Code
3. Laragon/XAMPP
4. Composer
5. CodeIgniter
6. Postman untuk testing API
7. MYSQL 

---

## 🔧  Installasi Tools

1. **Install Composer**  
   Download di: [https://getcomposer.org](https://getcomposer.org) 

2. **Install CodeIgniter**  
   Bisa dilakukan melalui Composer dengan perintah:
   ```bash
   composer create-project codeigniter4/appstarter (nama-folder-project) -vvv
   ```
   atau melalui website resmi CodeIgniter di : [https://www.codeigniter.com/download](https://www.codeigniter.com/download)
3. **Install Postman**  
   Postman desktop dapat diunduh melalui website resmi postman yaitu : [https://www.postman.com/downloads/](https://www.postman.com/downloads/)

   ---
## 🚀 Langkah - langkah Backend
1.   Pastikan PHP telah terinstall  
     Menggunakan perintah :
      ```bash
      php -v
      ```
      Notes : Untuk meminimalisir kesalahan dan error maka gunakan versi PHP terbaru  
2. Pastikan Composer juga telah terinstall  
   Menggunakan perintah :
   ```bash
   composer -v
   ```
   Notes : Composer harus benar benar terinstall agar install CodeIgniter melalui terminal (composer) bisa dilakukan.
3. Install CodeIgniter menggunakan composer atau terminal
   Buka terminal lalu ketikkan perintah :
    ```bash
   composer create-project codeigniter4/appstarter (nama-folder-project) -vvv
   ```
   Tunggu proses download package selesai dan jika proses selesai maka folder project CodeIgniter berhasil dibuat.
4. Buka folder project CodeIgniter di Visual Studio Code  
   Buka folder project di **C:/laragon/www/**
5. Buat Database untuk Uji coba API di lokal  
   Buka phpmyadmin di : [http://localhost/phpmyadmin/](http://localhost/phpmyadmin/)  
   Buat database dengan nama : pbfkel22  
   Buat tabel **users** menggunakan query :
   ```sql
   CREATE TABLE users (
   id_user int auto_increment PRIMARY KEY,
   username varchar(50) not null,
   email varchar(25) not null,
   password varchar(50) not null,
   role varchar(50) not NULL,
   nim char(9),
   nidn char(10),
   CONSTRAINT `users_fk1` FOREIGN KEY (`nim`) REFERENCES `mahasiswa` (`nim`) ON DELETE CASCADE ON UPDATE CASCADE,
   CONSTRAINT `users_fk2` FOREIGN KEY (`nidn`) REFERENCES `dosen_wali` (`nidn`) ON DELETE CASCADE ON UPDATE CASCADE
   );
   ```
   Buat tabel **mahasiswa** dengan perintah :
   ``` sql
   CREATE TABLE mahasiswa (
    nim CHAR(9) PRIMARY KEY,
    nama VARCHAR(50) NOT NULL,
    email VARCHAR(25) NOT NULL,
    alamat TEXT NOT NULL,
    nidn CHAR(10),
    CONSTRAINT mahasiswa_ibfk_1 FOREIGN KEY (nidn) REFERENCES dosen_wali (nidn) 
    ON DELETE CASCADE ON UPDATE CASCADE );
   ```
   Buat tabel **dosen_wali** dengan perintah :
   ```sql
   CREATE TABLE dosen_wali (
   nidn char(10) PRIMARY KEY,
   nama varchar(50) not null,
   email varchar(25) not null
   );
   ```
   Buat tabel **notifikasi** dengan perintah :
   ```sql
   CREATE TABLE notifikasi (
   id_notifikasi int auto_increment PRIMARY KEY,
   tipe varchar(25) not null,
   tanggal_kirim date not null,
   pesan text not null,
   nim char(9),
   nidn char(10),
   CONSTRAINT `notifikasi_ibfk_1` FOREIGN KEY (`nim`) REFERENCES `mahasiswa` (`nim`) ON DELETE CASCADE ON UPDATE CASCADE,
   CONSTRAINT `notifikasi_ibfk_2` FOREIGN KEY (`nidn`) REFERENCES `dosen_wali` (`nidn`) ON DELETE CASCADE ON UPDATE CASCADE
   );
   ```
   Buat tabel **pertemuan_perwalian** dengan perintah :
   ```sql
   CREATE TABLE pertemuan_perwalian (
    id_pertemuan INT AUTO_INCREMENT PRIMARY KEY,
    tanggal DATE NOT NULL,
    topik VARCHAR(255) NOT NULL,
    catatan VARCHAR(255) NOT NULL,
    saran_akademik VARCHAR(150) NOT NULL,
    nim CHAR(9),
    nidn CHAR(10),
    bulan_tahun VARCHAR(10) GENERATED ALWAYS AS (CONCAT(YEAR(tanggal), '-', LPAD(MONTH(tanggal), 2, '0'))) STORED, 
    CONSTRAINT pertemuan_ibfk_1 FOREIGN KEY (nim) REFERENCES mahasiswa (nim) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT pertemuan_ibfk_2 FOREIGN KEY (nidn) REFERENCES dosen_wali (nidn) ON DELETE CASCADE ON UPDATE CASCADE,
    UNIQUE (nim, nidn, tanggal) -- Mencegah duplikasi pertemuan pada tanggal yang sama
   );
   ```
6. Arahkan folder project ke database yang sudah dibuat.  
   Pada folder **app/config/database.php** ubah perintah :
   ```bash
    public array $default = [
        'DSN'          => '',
        'hostname'     => 'localhost',
        'username'     => '',
        'password'     => '',
        'database'     => '',
        'DBDriver'     => 'MySQLi',
        'DBPrefix'     => '',
        'pConnect'     => false,
        'DBDebug'      => true,
   ```
   menjadi
   ```bash
    public array $default = [
        'DSN'          => '',
        'hostname'     => 'localhost',
        'username'     => 'root',
        'password'     => '',
        'database'     => 'pbfkel22',
        'DBDriver'     => 'MySQLi',
        'DBPrefix'     => '',
        'pConnect'     => false,
   ```
   Perintah ini akan mengarahkan project ke database yang sudah dibuat yaitu  "pbfkel22".
7. Buat arsitektur MVC ( Model dan Controller serta Routes )  
   a. Model  
   Pada folder **app/Models** buat file model untuk setiap tabel untuk mengelola data dari database.  
   Buat **UserModel.php** :
   ```bash
   <?php
   namespace App\Models;
   use CodeIgniter\Model;
   class UserModel extends Model
   {
    protected $table = 'users'; 
    protected $primaryKey = 'id_user'; 
    protected $allowedFields = ['username', 'email', 'password', 'role', 'nidn','nim']; 
    protected $useTimestamps = false;
   }
   ```
   Buat **MahasiswaModel.php** :
   ```sql
   <?php
   namespace App\Models;
   use CodeIgniter\Model;
   class MahasiswaModel extends Model
   {
    protected $table = 'mahasiswa';  
    protected $primaryKey = 'nim';  
    protected $allowedFields = ['nim', 'nama', 'email', 'alamat', 'nidn'];  
    protected $validationRules = [
        'nim' => 'required|numeric|is_unique[mahasiswa.nim]',
        'nama' => 'required|min_length[3]',
        'email' => 'required|valid_email',
        'alamat' => 'required',
        'nidn' => 'required|numeric'
    ];
   }
   ```
   Buat **dosen_waliModel.php** :
   ```bash
   <?php
   namespace App\Models;
   use CodeIgniter\Model;
   class DosenWaliModel extends Model
   {
    protected $table = 'dosen_wali';
    protected $primaryKey = 'nidn';
    protected $allowedFields = ['nidn', 'nama', 'email']; 
    protected $useTimestamps = false;

    // Untuk validasi data
    protected $validationRules = [
        'nidn'   => 'required|numeric|is_unique[dosen_wali.nidn]',  
        'nama'   => 'required|min_length[3]',
        'email'  => 'required|valid_email',
    ];

    protected $validationMessages = [
        'nidn' => [
            'required' => 'NIDN harus diisi',
            'numeric' => 'NIDN harus berupa angka',
            'is_unique' => 'NIDN sudah terdaftar',
        ],
        'nama' => [
            'required' => 'Nama harus diisi',
            'min_length' => 'Nama harus memiliki panjang minimal 3 karakter',
        ],
        'email' => [
            'required' => 'Email harus diisi',
            'valid_email' => 'Email tidak valid',
        ]
      ];
   }

   ```
   Buat **NotifikasiModel.php** :
   ```bash
   <?php
   namespace App\Models;
   use CodeIgniter\Model;
   class NotifikasiModel extends Model
   {
    protected $table      = 'notifikasi';
    protected $primaryKey = 'id_notifikasi';
    protected $returnType = 'array';
    protected $allowedFields = ['tipe', 'tanggal_kirim', 'pesan', 'nim', 'nidn'];


    protected $validationRules = [
        'tipe'         => 'required|string',
        'tanggal_kirim'=> 'required|valid_date',
        'pesan'        => 'required|string|min_length[5]',
        'nim'          => 'required|numeric',
        'nidn'         => 'required|numeric',
    ];

    protected $validationMessages = [
        'tipe' => [
            'required' => 'Tipe tidak boleh kosong.',
        ],
        'tanggal_kirim' => [
            'valid_date' => 'Tanggal kirim tidak valid.',
        ],
        'pesan' => [
            'min_length' => 'Pesan harus memiliki minimal 5 karakter.',
        ],
    ];

    protected $skipValidation = false;
   }
   ```
   Buat **pertemuan_perwalianModel.php** :
   ```bash
   <?php
   namespace App\Models;
   use CodeIgniter\Model;
   class PertemuanPerwalianModel extends Model
   {
    protected $table      = 'pertemuan_perwalian';
    protected $primaryKey = 'id_pertemuan';
    protected $returnType = 'array';
    protected $allowedFields = ['tanggal', 'topik', 'catatan', 'saran_akademik', 'nim', 'nidn'];
    protected $validationRules = [
        'tanggal'         => 'required|valid_date',
        'topik'           => 'required|string',
        'catatan'         => 'required|string',
        'saran_akademik'  => 'required|string',
        'nim'             => 'required|numeric',
        'nidn'            => 'required|numeric',
        'bulan_tahun'     => 'required'
        
    ];

    protected $validationMessages = [
        'tanggal' => [
            'valid_date' => 'Tanggal tidak valid.',
        ],
        'bulan_tahun' => [
            'regex_match' => 'Bulan dan tahun harus dalam format YYYY-MM.',
        ]
    ];

    protected $skipValidation = false;
   }
   ```
b. Controller  
Pada folder **app/Controllers** buat file controller yang akan mengatur logika dari aplikasi.  
Buat **AuthController.php** :  
```bash
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
```
Buat **DosenWaliController.php** :  
```bash
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
```
Buat **MahasiswaController.php** :
```bash
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
```
Buat **NotifikasiController.php** :  
```bash
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
```
Buat **PertemuanPerwalianController.php** :  
```bash
<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use App\Models\PertemuanPerwalianModel;

class PertemuanPerwalianController extends Controller
{
    protected $pertemuanPerwalianModel;

    public function __construct() // menginisialisasi model
    {
        $this->pertemuanPerwalianModel = new PertemuanPerwalianModel();
    }

    public function store() //menyimpan data
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


    public function update($id_pertemuan) // mengubah pertemuan
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

   
        $pertemuan = $this->pertemuanPerwalianModel->find($id_pertemuan); // mencari apakah data ada di database atau tidak

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

    public function index() //menampilkan semua data perwalian
    {
        $pertemuan = $this->pertemuanPerwalianModel->findAll();
        if (empty($pertemuan)) {
            return $this->response->setStatusCode(404)->setJSON(['message' => 'Tidak ada data pertemuan perwalian']);
        }
        return $this->response->setStatusCode(200)->setJSON($pertemuan);
    }

    public function delete($id_pertemuan) // menghapus data perwalian
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
```
c. Routes  
Routes digunakan untuk membuat url dan controller dapat terhubung.  
Routes dapat ditemukan pada folder **app/config/Routes.php**  
```bash
<?php

use CodeIgniter\Router\RouteCollection;

/**
* @var RouteCollection $routes
*/
$routes->get('mahasiswa', 'MahasiswaController::index');                  
$routes->post('mahasiswa', 'MahasiswaController::store');               
$routes->put('mahasiswa/(:num)', 'MahasiswaController::update/$1');         
$routes->delete('mahasiswa/(:num)', 'MahasiswaController::delete/$1');   

$routes->get('dosen', 'DosenWaliController::index');                  
$routes->post('dosen', 'DosenWaliController::store');                 
$routes->put('dosen/(:num)', 'DosenWaliController::update/$1');        
$routes->delete('dosen/(:num)', 'DosenWaliController::delete/$1');   

$routes->get('notifikasi', 'NotifikasiController::index');                 
$routes->post('notifikasi', 'NotifikasiController::store');                 
$routes->put('notifikasi/(:num)', 'NotifikasiController::update/$1');         
$routes->delete('notifikasi/(:num)', 'NotifikasiController::delete/$1'); 

$routes->get('pertemuan', 'PertemuanPerwalianController::index');                   
$routes->post('pertemuan', 'PertemuanPerwalianController::store');                  
$routes->put('pertemuan/(:num)', 'PertemuanPerwalianController::update/$1');          
$routes->delete('pertemuan/(:num)', 'PertemuanPerwalianController::delete/$1');  

$routes->get('vmahasiswa', 'VMahasiswaController::index');          
$routes->post('vmahasiswa', 'VMahasiswaController::store');           
$routes->put('vmahasiswa/(:num)', 'VMahasiswaController::update/$1');  
$routes->delete('vmahasiswa/(:num)', 'VMahasiswaController::delete/$1');

$routes->get('vnotifikasi', 'VNotifikasiController::index');           
$routes->post('vnotifikasi', 'VNotifikasiController::store');           
$routes->put('vnotifikasi/(:num)', 'VNotifikasiController::update/$1');  

$routes->get('vpertemuan', 'VPertemuanPerwalianController::index');   

$routes->post('register', 'AuthController::register');
$routes->post('login', 'AuthController::login');
```


   



   
   
   

   
     
   
    
    
   
   
   
   

      
   




