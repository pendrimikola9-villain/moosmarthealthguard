<?php
require_once "config.php";
require_once "telegram.php";

$data = json_decode(file_get_contents("php://input"), true);

if (!$data || !isset($data['cow_id'])) {
    http_response_code(400);
    echo json_encode(["success" => false, "error" => "Data tidak valid"]);
    exit();
}

$cow_id = $conn->real_escape_string($data['cow_id']);
$nama = $conn->real_escape_string($data['nama'] ?? $cow_id);
$suhu = floatval($data['suhu_celsius'] ?? 0);
$bpm = intval($data['detak_jantung_bpm'] ?? 0);
$gerakan = floatval($data['nilai_gerakan'] ?? 0);

function statusSuhu($s) {
    if ($s > 40.5) return "danger";
    if ($s > 39.5) return "warning";
    return "normal";
}
function statusJantung($b) {
    if ($b > 100) return "danger";
    if ($b > 80) return "warning";
    return "normal";
}
function levelStress($g) {
    if ($g > 5) return "stress";
    if ($g > 2.5) return "waspada";
    return "tenang";
}

$status_suhu = statusSuhu($suhu);
$status_jantung = statusJantung($bpm);
$level_stress = levelStress($gerakan);

$cek = $conn->query("SELECT id FROM sensor_suhu WHERE cow_id = '$cow_id'");

if ($cek->num_rows > 0) {
    $conn->query("UPDATE sensor_suhu SET suhu_celsius=$suhu, status='$status_suhu', timestamp=NOW() WHERE cow_id='$cow_id'");
    $conn->query("UPDATE sensor_detak_jantung SET detak_jantung_bpm=$bpm, status='$status_jantung', timestamp=NOW() WHERE cow_id='$cow_id'");
    $conn->query("UPDATE sensor_aktivitas SET nilai_gerakan=$gerakan, level_stress='$level_stress', timestamp=NOW() WHERE cow_id='$cow_id'");
} else {
    $conn->query("INSERT INTO sensor_suhu (cow_id, nama, suhu_celsius, status) VALUES ('$cow_id','$nama',$suhu,'$status_suhu')");
    $conn->query("INSERT INTO sensor_detak_jantung (cow_id, nama, detak_jantung_bpm, status) VALUES ('$cow_id','$nama',$bpm,'$status_jantung')");
    $conn->query("INSERT INTO sensor_aktivitas (cow_id, nama, nilai_gerakan, level_stress) VALUES ('$cow_id','$nama',$gerakan,'$level_stress')");
}

if ($status_suhu !== "normal" || $status_jantung !== "normal" || $level_stress !== "tenang") {
    $labelSuhu = $status_suhu === "danger" ? "BAHAYA" : ($status_suhu === "warning" ? "WASPADA" : "Normal");
    $labelJantung = $status_jantung === "danger" ? "BAHAYA" : ($status_jantung === "warning" ? "WASPADA" : "Normal");
    $labelStress = $level_stress === "stress" ? "STRESS" : ($level_stress === "waspada" ? "WASPADA" : "Tenang");

    $pesan = "ALERT MooSmartHealthGuard\n\n";
    $pesan .= "Sapi: {$nama} ({$cow_id})\n";
    $pesan .= "Suhu: {$suhu}C - {$labelSuhu}\n";
    $pesan .= "Detak Jantung: {$bpm} bpm - {$labelJantung}\n";
    $pesan .= "Tingkat Kestressan: {$labelStress}\n\n";
    $pesan .= "Waktu: " . date("d/m/Y H:i:s") . "\n";
    $pesan .= "Segera periksa kondisi sapi.";

    kirimTelegram($pesan);
}

echo json_encode([
    "success" => true,
    "status_suhu" => $status_suhu,
    "status_jantung" => $status_jantung,
    "level_stress" => $level_stress
]);

$conn->close();
?>