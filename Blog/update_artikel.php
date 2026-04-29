<?php
require 'koneksi.php';

$id = $_POST['id'];
$judul = $_POST['judul'];
$isi = $_POST['isi'];
$id_penulis = $_POST['id_penulis'];
$id_kategori = $_POST['id_kategori'];

// Cek apakah ada upload gambar baru [cite: 198]
if (isset($_FILES['gambar']) && $_FILES['gambar']['error'] == 0) {
    // Jalankan validasi finfo dan upload (seperti di simpan_artikel.php) [cite: 295]
    $nama_file = time() . "_" . $_FILES['gambar']['name'];
    move_uploaded_file($_FILES['gambar']['tmp_name'], "uploads_artikel/" . $nama_file);

    // Update dengan gambar baru
    $stmt = $conn->prepare("UPDATE artikel SET judul=?, isi=?, id_penulis=?, id_kategori=?, gambar=? WHERE id=?");
    $stmt->bind_param("ssiisi", $judul, $isi, $id_penulis, $id_kategori, $nama_file, $id);
} else {
    // Update tanpa mengganti gambar [cite: 198]
    $stmt = $conn->prepare("UPDATE artikel SET judul=?, isi=?, id_penulis=?, id_kategori=? WHERE id=?");
    $stmt->bind_param("ssiii", $judul, $isi, $id_penulis, $id_kategori, $id);
}

$stmt->execute();
echo "Data diperbarui";
?>