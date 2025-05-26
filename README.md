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
   ```bash
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
   ``` bash
   CREATE TABLE mahasiswa (
    nim CHAR(9) PRIMARY KEY,
    nama VARCHAR(50) NOT NULL,
    email VARCHAR(25) NOT NULL,
    alamat TEXT NOT NULL,
    nidn CHAR(10),
    CONSTRAINT mahasiswa_ibfk_1 FOREIGN KEY (nidn) REFERENCES dosen_wali (nidn) 
    ON DELETE CASCADE ON UPDATE CASCADE );
   ```
   

   
     
   
    
    
   
   
   
   

      
   




