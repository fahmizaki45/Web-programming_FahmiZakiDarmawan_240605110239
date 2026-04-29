<?php
require 'koneksi.php';
$id = $_POST['id'];
$nama = $_POST['nama_kategori'];
$ket = $_POST['keterangan'];
$stmt = $conn->prepare("UPDATE kategori_artikel SET nama_kategori=?, keterangan=? WHERE id=?");
$stmt->bind_param("ssi", $nama, $ket, $id);
$stmt->execute();
echo "Kategori diperbarui";
?>