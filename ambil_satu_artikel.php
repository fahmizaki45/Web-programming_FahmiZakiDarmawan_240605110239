<?php
require 'koneksi.php';

$id = $_GET['id'];
$stmt = $conn->prepare("SELECT * FROM artikel WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$data = $result->fetch_assoc();

// Kirim data dalam format JSON agar bisa dibaca oleh JavaScript di index.php
echo json_encode($data);
?>