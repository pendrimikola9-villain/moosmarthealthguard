<?php
// Mengatur CORS agar frontend (React/Vite) bisa mengakses API ini tanpa terblokir browser
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: application/json");

// Memuat konfigurasi database (Port MySQL disesuaikan ke 3307 pada file config ini)
require_once "config.php"; 

/**
 * PERUBAHAN QUERY SQL:
 * 1. Mengubah 'JOIN' menjadi 'LEFT JOIN' agar data sapi tetap muncul meskipun 
 * salah satu tabel sensor (misal: detak jantung/aktivitas) masih kosong di database.
 * 2. Menyesuaikan nama kolom tabel 'sensor_aktivitas' berdasarkan database aktual:
 * - 'a.aktivitas' di-alias menjadi 'nilai_gerakan' untuk kebutuhan frontend.
 * - 'a.intensitas' di-alias menjadi 'level_stress' untuk kebutuhan frontend.
 */
$result = $conn->query("
    SELECT 
        s.cow_id,
        s.nama,
        s.suhu_celsius,
        s.status AS status_suhu,
        d.detak_jantung_bpm,
        d.status AS status_jantung,
        a.aktivitas AS nilai_gerakan, 
        a.intensitas AS level_stress,  
        s.timestamp
    FROM sensor_suhu s
    LEFT JOIN sensor_detak_jantung d ON s.cow_id = d.cow_id
    LEFT JOIN sensor_aktivitas a ON s.cow_id = a.cow_id
    ORDER BY s.cow_id ASC
");

$data = [];
// Menambahkan validasi pengecekan $result untuk mencegah error jika query gagal dieksekusi
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $data[] = $row;
    }
}

// Mengembalikan data dalam format JSON yang bersih dan siap dikonsumsi frontend
echo json_encode(["success" => true, "data" => $data]);

// Menutup koneksi database untuk menghemat resource server
$conn->close();
?>