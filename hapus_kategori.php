<?php
require 'koneksi.php';
$id = $_POST['id'];
$cek = $conn->prepare("SELECT id FROM artikel WHERE id_kategori = ?");
$cek->bind_param("i", $id);
$cek->execute();
if ($cek->get_result()->num_rows > 0) {
    echo "Gagal: Kategori masih digunakan di artikel!";
} else {
    $del = $conn->prepare("DELETE FROM kategori_artikel WHERE id = ?");
    $del->bind_param("i", $id);
    $del->execute();
    echo "Kategori dihapus";
}
?>