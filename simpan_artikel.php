<?php
require 'koneksi.php';

// Ambil data teks
$judul       = $_POST['judul'] ?? '';
$isi         = $_POST['isi'] ?? '';
$id_penulis  = $_POST['id_penulis'] ?? '';
$id_kategori = $_POST['id_kategori'] ?? '';

// Set Waktu sesuai soal UTS
date_default_timezone_set('Asia/Jakarta');
$hari = ["Minggu","Senin","Selasa","Rabu","Kamis","Jumat","Sabtu"][date("w")];
$tgl  = date("d-m-Y H:i");
$hari_tanggal = "$hari, $tgl";

// CEK GAMBAR (Inti Masalahnya)
if (isset($_FILES['gambar']) && $_FILES['gambar']['error'] == 0) {
    $nama_asal = $_FILES['gambar']['name'];
    $tmp_name  = $_FILES['gambar']['tmp_name'];
    
    // Beri nama unik agar tidak bentrok
    $ekstensi  = pathinfo($nama_asal, PATHINFO_EXTENSION);
    $nama_baru = "ART_" . time() . "." . $ekstensi;
    $tujuan    = "uploads_artikel/" . $nama_baru;

    if (move_uploaded_file($tmp_name, $tujuan)) {
        // Simpan ke database
        $stmt = $conn->prepare("INSERT INTO artikel (id_penulis, id_kategori, judul, isi, gambar, hari_tanggal) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("iissss", $id_penulis, $id_kategori, $judul, $isi, $nama_baru, $hari_tanggal);
        
        if ($stmt->execute()) {
            echo "Berhasil: Artikel sudah diterbitkan!";
        } else {
            echo "Gagal: Kesalahan database.";
        }
        $stmt->close();
    } else {
        echo "Gagal: Folder 'uploads_artikel' tidak ditemukan atau tidak bisa diisi.";
    }
} else {
    // Pesan ini muncul kalau PHP merasa $_FILES kosong
    echo "Gagal: Gambar belum dipilih atau ukuran terlalu besar.";
}
$conn->close();
?>