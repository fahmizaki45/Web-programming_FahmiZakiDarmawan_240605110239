<?php
require 'koneksi.php';

$id = $_POST['id'];
$nama_depan = $_POST['nama_depan'];
$nama_belakang = $_POST['nama_belakang'];
$user_name = $_POST['user_name'];
$password_baru = $_POST['password'] ?? ''; // Ambil input password baru dari form edit

// 1. Logika Update Dinamis
$sql = "UPDATE penulis SET nama_depan = ?, nama_belakang = ?, user_name = ?";
$params = [$nama_depan, $nama_belakang, $user_name];
$types = "sss";

// 2. CEK: Jika password baru diisi, tambahkan ke query
if (!empty($password_baru)) {
    $hash_baru = password_hash($password_baru, PASSWORD_BCRYPT);
    $sql .= ", password = ?";
    $params[] = $hash_baru;
    $types .= "s";
}

// 3. CEK: Jika ada upload foto profil baru
if (isset($_FILES['foto']) && $_FILES['foto']['error'] == 0) {
    $ext = pathinfo($_FILES['foto']['name'], PATHINFO_EXTENSION);
    $nama_foto = "user_" . time() . "." . $ext;
    move_uploaded_file($_FILES['foto']['tmp_name'], "uploads_penulis/" . $nama_foto);
    
    $sql .= ", foto = ?";
    $params[] = $nama_foto;
    $types .= "s";
}

// 4. Tutup Query dengan WHERE ID
$sql .= " WHERE id = ?";
$params[] = $id;
$types .= "i";

// Eksekusi Prepare Statement
$stmt = $conn->prepare($sql);
$stmt->bind_param($types, ...$params);

if ($stmt->execute()) {
    echo "Berhasil: Data penulis telah diperbarui.";
} else {
    echo "Gagal: " . $conn->error;
}

$stmt->close();
$conn->close();
?>