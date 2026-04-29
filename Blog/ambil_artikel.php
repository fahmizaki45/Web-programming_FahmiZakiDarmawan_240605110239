<?php
require 'koneksi.php';

// Ambil data dengan JOIN agar muncul nama asli, bukan ID
$sql = "SELECT artikel.*, penulis.nama_depan, kategori_artikel.nama_kategori 
        FROM artikel 
        JOIN penulis ON artikel.id_penulis = penulis.id 
        JOIN kategori_artikel ON artikel.id_kategori = kategori_artikel.id 
        ORDER BY artikel.id DESC";
$result = $conn->query($sql);
?>

<div class="flex justify-between items-center mb-6">
    <h2 class="text-2xl font-bold text-gray-800">Daftar Artikel</h2>
    <button onclick="bukaModalTambahArtikel()" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg font-bold shadow-md transition-all flex items-center">
    <i class="fa-solid fa-plus mr-2"></i> Tambah Artikel
</button>
</div>

<div class="overflow-x-auto border border-gray-200 rounded-xl shadow-sm">
    <table class="min-w-full bg-white">
        <thead class="bg-gray-50 text-gray-500 text-xs uppercase font-bold text-center">
            <tr>
                <th class="px-4 py-4 border-b">Gambar</th>
                <th class="px-4 py-4 border-b text-left">Judul</th>
                <th class="px-4 py-4 border-b">Kategori</th>
                <th class="px-4 py-4 border-b">Penulis</th>
                <th class="px-4 py-4 border-b">Tanggal</th> <th class="px-4 py-4 border-b text-center">Aksi</th>
            </tr>
        </thead>
        <tbody class="divide-y text-sm text-center">
            <?php while($row = $result->fetch_assoc()): ?>
            <tr class="hover:bg-gray-50 transition">
                <td class="px-4 py-4">
                    <img src="uploads_artikel/<?= $row['gambar'] ?>" class="w-16 h-10 object-cover rounded shadow-sm mx-auto">
                </td>
                <td class="px-4 py-4 font-bold text-gray-800 text-left"><?= htmlspecialchars($row['judul']) ?></td>
                <td class="px-4 py-4">
                    <span class="bg-blue-100 text-blue-700 px-2 py-1 rounded text-[10px] font-bold"><?= htmlspecialchars($row['nama_kategori']) ?></span>
                </td>
                <td class="px-4 py-4 text-gray-600"><?= htmlspecialchars($row['nama_depan']) ?></td>
                <td class="px-4 py-4 text-gray-400 text-[10px]"><?= $row['hari_tanggal'] ?></td>
                <td class="px-4 py-4 text-center">
    <div class="flex justify-center gap-2">
        <button onclick="editData('artikel', <?= $row['id'] ?>)" class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-1.5 rounded-md text-xs font-bold transition shadow-sm">
            <i class="fa-solid fa-pen-to-square"></i> Edit
        </button>
        <button onclick="hapusData('artikel', <?= $row['id'] ?>)" class="bg-red-500 hover:bg-red-600 text-white px-3 py-1.5 rounded-md text-xs font-bold transition shadow-sm">
            <i class="fa-solid fa-trash"></i> Hapus
        </button>
    </div>
</td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>