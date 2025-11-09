<?php
// === sambungkan ke database ===
include 'koneksi.php';

// pastikan metode request adalah POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // ambil data dari permintaan
    $height = isset($_POST['height']) ? floatval($_POST['height']) : 0;
    $weight = isset($_POST['weight']) ? floatval($_POST['weight']) : 0;
    $bmi    = isset($_POST['bmi']) ? floatval($_POST['bmi']) : 0;
    $gender = isset($_POST['gender']) ? $_POST['gender'] : 'unknown';
    $ideal  = isset($_POST['ideal_weight']) ? floatval($_POST['ideal_weight']) : null;
    $note   = isset($_POST['note']) ? $_POST['note'] : null;

    // validasi sederhana
    if ($height <= 0 || $weight <= 0 || $bmi <= 0) {
        echo json_encode(["status" => "error", "message" => "Data tidak valid."]);
        exit;
    }

    // ambil IP dan user agent (opsional)
    $ip = $_SERVER['REMOTE_ADDR'];
    $ua = $_SERVER['HTTP_USER_AGENT'];

    // siapkan query simpan data
    $stmt = $conn->prepare("INSERT INTO bmi_results (height_cm, weight_kg, bmi, gender, ideal_weight, note, ip_address, user_agent) 
                            VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("dddsdsss", $height, $weight, $bmi, $gender, $ideal, $note, $ip, $ua);

    if ($stmt->execute()) {
        echo json_encode(["status" => "success", "message" => "Data BMI berhasil disimpan."]);
    } else {
        echo json_encode(["status" => "error", "message" => "Gagal menyimpan data: " . $stmt->error]);
    }

    $stmt->close();
} else {
    echo json_encode(["status" => "error", "message" => "Metode request tidak diizinkan."]);
}

$conn->close();
?>
