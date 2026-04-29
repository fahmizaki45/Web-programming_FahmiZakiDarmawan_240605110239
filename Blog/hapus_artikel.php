<?php
require 'koneksi.php';
$id = $_POST['id'];

// Ambil nama file gambar sebelum data dihapus
$query = $conn->prepare("SELECT gambar FROM artikel WHERE id = ?");
$query->bind_param("i", $id);
$query->execute();
$data = $query->get_result()->fetch_assoc();

if ($data) {
    unlink("uploads_artikel/" . $data['gambar']); // Hapus file fisik [cite: 288]
    $del = $conn->prepare("DELETE FROM artikel WHERE id = ?");
    $del->bind_param("i", $id);
    $del->execute();
    echo "Artikel dan gambar berhasil dihapus";
}
?>