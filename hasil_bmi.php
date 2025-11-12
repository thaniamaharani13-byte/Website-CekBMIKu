<?php
include 'koneksi.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $height = floatval($_POST['height']);
    $weight = floatval($_POST['weight']);
    $bmi    = floatval($_POST['bmi']);
    $gender = $_POST['gender'];
    $ideal  = isset($_POST['ideal_weight']) ? $_POST['ideal_weight'] : null;
    $note   = isset($_POST['note']) ? $_POST['note'] : null;

    if ($height <= 0 || $weight <= 0 || $bmi <= 0) {
        echo json_encode(["status" => "error", "message" => "Data tidak valid."]);
        exit;
    }

    $ideal = ($ideal !== null && $ideal !== "") ? floatval($ideal) : null;
    $note  = ($note !== null && $note !== "") ? $note : null;

    $ip = $_SERVER['REMOTE_ADDR'];
    $ua = $_SERVER['HTTP_USER_AGENT'];

    $stmt = $conn->prepare("INSERT INTO bmi_results (height_cm, weight_kg, bmi, gender, ideal_weight, note, ip_address, user_agent) 
                            VALUES (?, ?, ?, ?, ?, ?, ?, ?)");

    if (!$stmt) {
        die("Prepare gagal: " . $conn->error);
    }

    $stmt->bind_param("dddsdsss", $height, $weight, $bmi, $gender, $ideal, $note, $ip, $ua);

    if ($stmt->execute()) {
        echo json_encode(["status" => "success", "message" => "Data BMI berhasil disimpan."]);
    } else {
        echo json_encode(["status" => "error", "message" => "Gagal menyimpan data: " . $stmt->error]);
    }

    $stmt->close();
}
$conn->close();
?>
