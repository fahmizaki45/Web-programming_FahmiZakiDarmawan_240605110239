<?php
require 'koneksi.php';

// Rules: Tetap pertahankan logika JSON untuk dropdown artikel
if (isset($_GET['format']) && $_GET['format'] == 'json') {
    $result = $conn->query("SELECT id, nama_depan, nama_belakang FROM penulis");
    echo json_encode($result->fetch_all(MYSQLI_ASSOC));
    exit;
}

$result = $conn->query("SELECT * FROM penulis ORDER BY id DESC");
?>

<div class="flex justify-between items-center mb-6">
    <h2 class="text-2xl font-bold text-gray-800">Daftar Penulis</h2>
    <button onclick="bukaModalTambahPenulis()" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg font-bold shadow-md transition-all flex items-center">
    <i class="fa-solid fa-user-plus mr-2"></i> Tambah Penulis
</button>
</div>

<div class="overflow-x-auto border border-gray-200 rounded-xl shadow-sm">
    <table class="min-w-full bg-white">
        <thead class="bg-gray-50 text-gray-500 text-xs uppercase font-bold text-center">
            <tr>
                <th class="px-4 py-4 border-b">Foto</th>
                <th class="px-4 py-4 border-b">Nama Lengkap</th>
                <th class="px-4 py-4 border-b">Username</th>
                <th class="px-4 py-4 border-b">Password </th> <th class="px-4 py-4 border-b text-center">Aksi</th>
            </tr>
        </thead>
        <tbody class="divide-y text-sm text-center">
            <?php while($row = $result->fetch_assoc()): ?>
            <tr class="hover:bg-gray-50 transition">
                <td class="px-4 py-4 flex justify-center">
                    <?php $foto = (!empty($row['foto']) && file_exists("uploads_penulis/".$row['foto'])) ? $row['foto'] : "default.png"; ?>
                    <img src="uploads_penulis/<?= $foto ?>" class="w-10 h-10 rounded-full object-cover border shadow-sm">
                </td>
                <td class="px-4 py-4 font-semibold"><?= htmlspecialchars($row['nama_depan'] . " " . $row['nama_belakang']) ?></td>
                <td class="px-4 py-4 text-blue-600 font-medium">@<?= htmlspecialchars($row['user_name']) ?></td>
                <td class="px-4 py-4 font-mono text-[10px] text-gray-400">
                    <div class="truncate w-24 mx-auto"><?= $row['password'] ?></div>
                </td>
                <td class="px-4 py-4 text-center">
    <div class="flex justify-center gap-2">
        <button onclick="editData('penulis', <?= $row['id'] ?>)" class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-1.5 rounded-md text-xs font-bold transition shadow-sm">
            <i class="fa-solid fa-pen-to-square"></i> Edit
        </button>
        <button onclick="hapusData('penulis', <?= $row['id'] ?>)" class="bg-red-500 hover:bg-red-600 text-white px-3 py-1.5 rounded-md text-xs font-bold transition shadow-sm">
            <i class="fa-solid fa-trash"></i> Hapus
        </button>
    </div>
</td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>