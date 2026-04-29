<?php
require 'koneksi.php';

// Format JSON untuk dropdown (Akan dipakai di menu Artikel nanti)
if (isset($_GET['format']) && $_GET['format'] == 'json') {
    $result = $conn->query("SELECT id, nama_kategori FROM kategori_artikel ORDER BY nama_kategori ASC");
    echo json_encode($result->fetch_all(MYSQLI_ASSOC));
    exit;
}

$result = $conn->query("SELECT * FROM kategori_artikel ORDER BY id DESC");
?>

<div class="flex justify-between items-center mb-6">
    <h2 class="text-2xl font-bold text-gray-800">Data Kategori Artikel</h2>
    <button onclick="bukaModalTambahKategori()" class="bg-green-600 hover:bg-green-700 text-white px-6 py-2 rounded-lg transition shadow-md font-bold flex items-center">
        <i class="fa-solid fa-plus mr-2"></i> Tambah Kategori
    </button>
</div>

<div class="overflow-x-auto border border-gray-200 rounded-xl shadow-sm">
    <table class="min-w-full bg-white">
        <thead class="bg-gray-50 text-gray-500 text-xs uppercase font-bold">
            <tr>
                <th class="px-6 py-4 text-left border-b">Nama Kategori</th>
                <th class="px-6 py-4 text-left border-b">Keterangan</th>
                <th class="px-6 py-4 text-center border-b">Aksi</th>
            </tr>
        </thead>
        <tbody class="divide-y text-sm">
            <?php if ($result->num_rows > 0): ?>
                <?php while($row = $result->fetch_assoc()): ?>
                <tr class="hover:bg-gray-50 transition">
                    <td class="px-6 py-4 font-bold text-gray-900"><?= htmlspecialchars($row['nama_kategori']) ?></td>
                    <td class="px-6 py-4 text-gray-500"><?= htmlspecialchars($row['keterangan']) ?></td>
                    <td class="px-4 py-4 text-center">
    <div class="flex justify-center gap-2">
        <button onclick="editData('kategori', <?= $row['id'] ?>)" class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-1.5 rounded-md text-xs font-bold transition shadow-sm">
            <i class="fa-solid fa-pen-to-square"></i> Edit
        </button>
        <button onclick="hapusData('kategori', <?= $row['id'] ?>)" class="bg-red-500 hover:bg-red-600 text-white px-3 py-1.5 rounded-md text-xs font-bold transition shadow-sm">
            <i class="fa-solid fa-trash"></i> Hapus
        </button>
    </div>
</td>
                </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr><td colspan="3" class="p-10 text-center text-gray-400 italic">Belum ada data kategori.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>