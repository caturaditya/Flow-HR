Flow HR - aplikasi HRIS sederhana

Ringkasan:
- PHP + MySQL (dirancang untuk XAMPP)
- Fitur awal: autentikasi, manajemen karyawan, departemen, absensi sederhana, dashboard

Langkah cepat (Windows + XAMPP):
1. Salin folder ini ke `C:\xampp\htdocs\miniHRIS`.
2. Pastikan MySQL berjalan di XAMPP, buka phpMyAdmin buat database bernama `minihris` (atau ubah nama di `inc/config.php`).
3. Import `sql/init.sql` ke database (opsional) atau biarkan aplikasi membuat tabel saat dijalankan.
4. Buka http://localhost/miniHRIS/ di browser. Default admin: username `admin`, password `admin` (ubah setelah login).

Catatan keamanan: ini contoh sederhana untuk development. Jangan gunakan tanpa review keamanan di production.
