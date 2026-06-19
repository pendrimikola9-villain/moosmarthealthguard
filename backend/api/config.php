<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: application/json");

$host = "localhost";
$user = "root";
$pass = "";
$db   = "monitoring_sapi";

$conn = new mysqli($host, $user, $pass, $db, 3307);

if ($conn->connect_error) {
    http_response_code(500);
    echo json_encode(["error" => "Koneksi gagal: " . $conn->connect_error]);
    exit();
}
?>