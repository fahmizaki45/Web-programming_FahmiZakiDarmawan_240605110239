<?php
require 'koneksi.php';

$id = $_POST['id'];

// 1. CEK SESUAI POIN 6: Apakah penulis ini punya artikel?
$cek_artikel = $conn->prepare("SELECT id FROM artikel WHERE id_penulis = ?");
$cek_artikel->bind_param("i", $id);
$cek_artikel->execute();
$result = $cek_artikel->get_result();

if ($result->num_rows > 0) {
    // Jika masih ada artikel, cegah penghapusan
    echo "Gagal: Penulis ini masih memiliki artikel. Hapus semua artikelnya terlebih dahulu!";
} else {
    // 2. Jika tidak ada artikel, baru boleh dihapus
    $stmt = $conn->prepare("DELETE FROM penulis WHERE id = ?");
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        echo "Berhasil: Data penulis telah dihapus.";
    } else {
        echo "Gagal: Terjadi kesalahan saat menghapus.";
    }
    $stmt->close();
}

$cek_artikel->close();
$conn->close();
?>