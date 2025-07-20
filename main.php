<?php
require_once 'koneksi.php';

// Set response jadi JSON
header('Content-Type: application/json');

// Header untuk CORS (jika diperlukan)
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, GET, OPTIONS');
// header('Access-Control-Allow-Headers: Content-Type'); // DIHAPUS SEMENTARA

// Handle preflight request
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    exit(0);
}else if($_SERVER['REQUEST_METHOD'] === 'POST'){
    // Ambil data dari body request
    $json = file_get_contents('php://input');

    // Decode JSON jadi array PHP
    $data = json_decode($json, true);

    // Cek apakah data valid
    if (!isset($data['name']) || !isset($data['message'])) {
        echo json_encode(['success' => false, 'error' => 'Data tidak lengkap']);
        exit;
    }

    $newIdMessage = generateNewIdMessage($conn);

    // Prepared statement untuk keamanan maksimal
    $stmt = $conn->prepare("INSERT INTO messages (id_message, name, message) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $newIdMessage, $data['name'], $data['message']);

    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Pesan berhasil disimpan']);
    } else {
        echo json_encode(['success' => false, 'error' => 'Gagal menyimpan pesan']);
    }

    $stmt->close();
    $conn->close();
}else if($_SERVER['REQUEST_METHOD'] === 'GET') {
    $query = "SELECT name, message, created_at FROM messages ORDER BY id_message DESC";
    $result = $conn->query($query);

    if($result->num_rows > 0){
        $messages = $result->fetch_all(MYSQLI_ASSOC);
        echo json_encode($messages);
    }else {
        echo json_encode([]);
    }

    $conn->close();
}
?>
