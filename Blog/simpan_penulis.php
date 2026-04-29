<?php
require 'koneksi.php';

// Ambil data dari POST
$nama_depan    = $_POST['nama_depan'];
$nama_belakang = $_POST['nama_belakang'];
$user_name     = $_POST['user_name'];
$password      = $_POST['password'];

// 1. Enkripsi password sesuai instruksi soal 
$password_hashed = password_hash($password, PASSWORD_BCRYPT);

$nama_file_final = "default.png"; // Default awal

// 2. Logika Upload & Validasi Foto [cite: 295, 299]
if (isset($_FILES['foto']) && $_FILES['foto']['error'] == 0) {
    $tmp_file   = $_FILES['foto']['tmp_name'];
    $ukuran_file = $_FILES['foto']['size'];
    
    // Validasi ukuran maksimal 2MB 
    if ($ukuran_file <= 2000000) {
        // Validasi tipe file dengan finfo (WAJIB) 
        $finfo = new finfo(FILEINFO_MIME_TYPE);
        $mime_type = $finfo->file($tmp_file);
        
        $allowed = ['image/jpeg', 'image/png', 'image/jpg'];
        
        if (in_array($mime_type, $allowed)) {
            $ext = pathinfo($_FILES['foto']['name'], PATHINFO_EXTENSION);
            $nama_file_final = time() . "_" . $user_name . "." . $ext;
            move_uploaded_file($tmp_file, "uploads_penulis/" . $nama_file_final);
        }
    }
}

// 3. Simpan ke Database menggunakan Prepared Statements 
$stmt = $conn->prepare("INSERT INTO penulis (nama_depan, nama_belakang, user_name, password, foto) VALUES (?, ?, ?, ?, ?)");
$stmt->bind_param("sssss", $nama_depan, $nama_belakang, $user_name, $password_hashed, $nama_file_final);

if ($stmt->execute()) {
    echo "Data penulis berhasil disimpan!";
} else {
    echo "Gagal menyimpan data: " . $conn->error;
}

$stmt->close();
$conn->close();
?>