MooSmartHealthGuard
Sistem Monitoring Kesehatan Sapi Berbasis IoT


Cara Menjalankan Project
XAMPP sudah terinstall dan running (Apache + MySQL)
Node.js sudah terinstall
Git sudah terinstall

1. Clone Repository

bashgit clone https://github.com/divaaulianisa03/moosmarthealthguard.git
cd moosmarthealthguard


2. Import Database

Buka CMD:
bashcd C:\xampp\mysql\bin
mysql -u root -p -e "CREATE DATABASE monitoring_sapi;"
mysql -u root -p monitoring_sapi < C:\xampp\htdocs\moosmarthealthguard\database.sql

Atau lewat phpMyAdmin:
Buka http://localhost/phpmyadmin
Buat database baru dengan nama monitoring_sapi
Klik Import, pilih file database.sql, lalu klik Go



3. Pindahkan Folder API ke htdocs
Salin folder api ke:
C:\xampp\htdocs\monitoring-sapi\api\


4. Install Dependencies Frontend
bashcd frontend
npm install


5. Jalankan
bashnpm run dev
Buka browser: http://localhost:5173
