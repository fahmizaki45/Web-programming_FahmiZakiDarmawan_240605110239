<?php
require 'koneksi.php';
$nama = $_POST['nama_kategori'];
$ket = $_POST['keterangan'];
$stmt = $conn->prepare("INSERT INTO kategori_artikel (nama_kategori, keterangan) VALUES (?, ?)");
$stmt->bind_param("ss", $nama, $ket);
$stmt->execute() ? print("Kategori disimpan") : print("Gagal: Nama kategori sudah ada");
?>