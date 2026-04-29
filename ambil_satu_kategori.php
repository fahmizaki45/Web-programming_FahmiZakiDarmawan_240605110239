<?php
require 'koneksi.php';

$id = $_GET['id'];
// Pastikan nama tabel sesuai dengan yang kamu gunakan (kategori_artikel)
$stmt = $conn->prepare("SELECT id, nama_kategori, keterangan FROM kategori_artikel WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();

echo json_encode($stmt->get_result()->fetch_assoc());
?>