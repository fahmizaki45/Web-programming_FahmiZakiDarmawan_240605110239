<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>CMS Blog - Fahmi Zaki</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body class="bg-gray-100 h-screen overflow-hidden flex flex-col">

    <header class="bg-slate-800 text-white shadow-md py-4 px-6 z-10">
        <div class="flex justify-between items-center">
            <h1 class="text-xl font-bold">CMS Blog - Fahmi Zaki</h1>
            <div class="text-sm text-slate-400">UTS Pemrograman Web 2026</div>
        </div>
    </header>

    <div class="flex flex-1 overflow-hidden">
        <nav class="w-64 bg-slate-700 text-white flex flex-col">
            <div class="p-4 text-xs font-semibold text-slate-400 uppercase tracking-widest">Menu Utama</div>
            <ul class="flex-1">
                <li><a href="javascript:void(0)" onclick="loadMenu('penulis', this)" class="sidebar-link flex items-center py-3 px-6 hover:bg-slate-600 transition-colors"><i class="fa-solid fa-users w-6"></i> Kelola Penulis</a></li>
                <li><a href="javascript:void(0)" onclick="loadMenu('artikel', this)" class="sidebar-link flex items-center py-3 px-6 hover:bg-slate-600 transition-colors"><i class="fa-solid fa-file-alt w-6"></i> Kelola Artikel</a></li>
                <li><a href="javascript:void(0)" onclick="loadMenu('kategori', this)" class="sidebar-link flex items-center py-3 px-6 hover:bg-slate-600 transition-colors"><i class="fa-solid fa-tags w-6"></i> Kelola Kategori</a></li>
            </ul>
        </nav>
        <main class="flex-1 overflow-y-auto p-8">
            <div id="main-content" class="bg-white rounded-xl shadow-sm p-6 min-h-full border border-gray-200">
                <div class="text-center py-20">
                    <h2 class="text-2xl font-bold text-gray-800">Selamat Datang, Fahmi!</h2>
                    <p class="text-gray-500">Pilih menu di samping untuk mengelola konten.</p>
                </div>
            </div>
        </main>
    </div>

    <!-- Modal Utama (Tambah / Edit) -->
    <div id="modal-container" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4">
        <div class="bg-white rounded-lg shadow-xl w-full max-w-lg">
            <div class="bg-gray-100 px-6 py-4 border-b flex justify-between items-center">
                <h3 id="modal-title" class="font-bold text-gray-700">Form Data</h3>
                <button onclick="tutupModal()" class="text-gray-400 hover:text-gray-600 text-2xl">&times;</button>
            </div>
            <div id="modal-body" class="p-6 overflow-y-auto max-h-[80vh]"></div>
        </div>
    </div>

    <!-- Modal Konfirmasi Hapus -->
    <div id="modal-hapus" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-[60] p-4">
        <div class="bg-white rounded-xl shadow-2xl w-full max-w-sm p-6 text-center border border-gray-100">
            <div class="w-16 h-16 bg-red-100 text-red-600 rounded-full flex items-center justify-center mx-auto mb-4">
                <i class="fa-solid fa-trash-can text-2xl"></i>
            </div>
            <h3 class="text-xl font-bold text-gray-800 mb-2">Hapus data ini?</h3>
            <p class="text-gray-500 mb-6 text-sm">Data yang dihapus tidak dapat dikembalikan.</p>
            <div class="flex gap-3">
                <button onclick="tutupModalHapus()" class="flex-1 bg-gray-400 hover:bg-gray-500 text-white py-2 rounded-lg font-semibold transition">Batal</button>
                <button id="btn-konfirmasi-hapus" class="flex-1 bg-red-500 hover:bg-red-600 text-white py-2 rounded-lg font-semibold transition">Ya, Hapus</button>
            </div>
        </div>
    </div>

    <script>
        // =====================================================
        // 1. NAVIGASI SIDEBAR
        // =====================================================
        function loadMenu(menu, element) {
            const links = document.querySelectorAll('.sidebar-link');
            links.forEach(l => l.classList.remove('bg-slate-600'));
            if (element) element.classList.add('bg-slate-600');

            fetch(`ambil_${menu}.php`)
                .then(res => {
                    if (!res.ok) throw new Error("File PHP tidak ditemukan: ambil_" + menu + ".php");
                    return res.text();
                })
                .then(html => {
                    const target = document.getElementById('main-content');
                    if (target) target.innerHTML = html;
                })
                .catch(err => {
                    const target = document.getElementById('main-content');
                    if (target) target.innerHTML = `<div class="text-center py-20 text-red-500"><i class="fa-solid fa-circle-exclamation text-4xl mb-3"></i><p class="font-bold">Gagal memuat halaman</p><p class="text-sm text-gray-400">${err.message}</p></div>`;
                });
        }

        // =====================================================
        // 2. FUNGSI MODAL DASAR
        // =====================================================
        function tampilModal(title, html) {
            const mTitle = document.getElementById('modal-title');
            const mBody  = document.getElementById('modal-body');
            const mCont  = document.getElementById('modal-container');
            if (mTitle && mBody && mCont) {
                mTitle.innerText = title;
                mBody.innerHTML  = html;
                mCont.classList.remove('hidden');
            }
        }

        function tutupModal() {
            const mCont = document.getElementById('modal-container');
            if (mCont) mCont.classList.add('hidden');
        }

        function tutupModalHapus() {
            const modalHapus = document.getElementById('modal-hapus');
            if (modalHapus) modalHapus.classList.add('hidden');
        }

        // =====================================================
        // 3. MODAL TAMBAH PENULIS
        // =====================================================
        function bukaModalTambahPenulis() {
            const html = `
                <form id="form-penulis" onsubmit="event.preventDefault(); prosesSimpan('penulis');">
                    <div class="grid grid-cols-2 gap-4 mb-3">
                        <div>
                            <label class="block text-xs font-bold mb-1">Nama Depan</label>
                            <input type="text" name="nama_depan" class="w-full border p-2 rounded text-sm" required>
                        </div>
                        <div>
                            <label class="block text-xs font-bold mb-1">Nama Belakang</label>
                            <input type="text" name="nama_belakang" class="w-full border p-2 rounded text-sm" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="block text-xs font-bold mb-1">Username</label>
                        <input type="text" name="user_name" class="w-full border p-2 rounded text-sm" required>
                    </div>
                    <div class="mb-3">
                        <label class="block text-xs font-bold mb-1">Password</label>
                        <input type="password" name="password" class="w-full border p-2 rounded text-sm" required>
                    </div>
                    <div class="mb-4">
                        <label class="block text-xs font-bold mb-1">Foto Profil</label>
                        <input type="file" name="foto" class="w-full text-sm" accept="image/*" required>
                    </div>
                    <div class="flex justify-end gap-2">
                        <button type="button" onclick="tutupModal()" class="bg-gray-400 text-white px-4 py-2 rounded text-sm">Batal</button>
                        <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded text-sm font-bold">Simpan Penulis</button>
                    </div>
                </form>`;
            tampilModal("Tambah Penulis Baru", html);
        }

        // =====================================================
        // 4. MODAL TAMBAH KATEGORI
        // =====================================================
        function bukaModalTambahKategori() {
            const html = `
                <form id="form-kategori" onsubmit="event.preventDefault(); prosesSimpan('kategori');">
                    <div class="mb-4">
                        <label class="block text-sm font-bold mb-2 text-gray-700">Pilih Kategori Artikel</label>
                        <select id="pilihan_kategori" class="w-full border p-2 rounded mb-2 outline-none focus:ring-2 focus:ring-green-500"
                                onchange="cekKategoriLainnya(this.value)">
                            <option value="">-- Pilih Kategori Umum --</option>
                            <option value="Teknologi">Teknologi</option>
                            <option value="Pendidikan">Pendidikan</option>
                            <option value="Kesehatan">Kesehatan</option>
                            <option value="Hiburan">Hiburan</option>
                            <option value="Olahraga">Olahraga</option>
                            <option value="Lainnya">Kategori Lainnya (Ketik Manual)</option>
                        </select>
                        <input type="text" id="input_manual" name="nama_kategori"
                               class="hidden w-full border p-2 rounded outline-none focus:ring-2 focus:ring-green-500"
                               placeholder="Ketik nama kategori baru...">
                    </div>
                    <div class="mb-4">
                        <label class="block text-sm font-bold mb-2 text-gray-700">Keterangan</label>
                        <textarea name="keterangan" class="w-full border p-2 rounded h-24 outline-none focus:ring-2 focus:ring-green-500"
                                  placeholder="Deskripsi singkat..."></textarea>
                    </div>
                    <div class="flex justify-end gap-3">
                        <button type="button" onclick="tutupModal()" class="bg-gray-400 text-white px-4 py-2 rounded">Batal</button>
                        <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded font-bold hover:bg-green-700">Simpan Kategori</button>
                    </div>
                </form>`;
            tampilModal("Tambah Kategori", html);
        }

        function cekKategoriLainnya(val) {
            const inputManual = document.getElementById('input_manual');
            if (val === 'Lainnya') {
                inputManual.classList.remove('hidden');
                inputManual.value = "";
                inputManual.required = true;
            } else {
                inputManual.classList.add('hidden');
                inputManual.value = val;
                inputManual.required = false;
            }
        }
        function cekKategoriLainnyaEdit(val) {
    const inputManual = document.getElementById('edit_input_manual');
    if (val === 'Lainnya') {
        inputManual.classList.remove('hidden');
        inputManual.focus();
    } else {
        inputManual.classList.add('hidden');
        inputManual.value = val; // Set value input sesuai pilihan select
    }
}

        // =====================================================
        // 5. MODAL TAMBAH ARTIKEL (Dropdown Dinamis)
        // =====================================================
        async function bukaModalTambahArtikel() {
            tampilModal("Tambah Artikel", "<p class='text-center p-5'>Mengambil data pilihan...</p>");
            try {
                const [resP, resK] = await Promise.all([
                    fetch('ambil_penulis.php?format=json').then(r => r.json()),
                    fetch('ambil_kategori.php?format=json').then(r => r.json())
                ]);

                if (resP.length === 0 || resK.length === 0) {
                    tampilModal("Peringatan", "<p class='text-red-500 p-5'>Data Penulis atau Kategori masih kosong. Isi dulu di menu terkait!</p>");
                    return;
                }

                const optP = resP.map(p => `<option value="${p.id}">${p.nama_depan} ${p.nama_belakang}</option>`).join('');
                const optK = resK.map(k => `<option value="${k.id}">${k.nama_kategori}</option>`).join('');

                const html = `
                    <form id="form-artikel" onsubmit="event.preventDefault(); prosesSimpan('artikel');">
                        <div class="mb-3">
                            <label class="block text-xs font-bold mb-1">Judul Artikel</label>
                            <input type="text" name="judul" class="w-full border p-2 rounded text-sm outline-none focus:ring-2 focus:ring-indigo-500" placeholder="Ketik judul artikel..." required>
                        </div>
                        <div class="grid grid-cols-2 gap-3 mb-3">
                            <div>
                                <label class="block text-xs font-bold mb-1">Penulis</label>
                                <select name="id_penulis" class="w-full border p-2 rounded text-sm outline-none focus:ring-2 focus:ring-indigo-500">${optP}</select>
                            </div>
                            <div>
                                <label class="block text-xs font-bold mb-1">Kategori</label>
                                <select name="id_kategori" class="w-full border p-2 rounded text-sm outline-none focus:ring-2 focus:ring-indigo-500">${optK}</select>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="block text-xs font-bold mb-1">Isi Konten</label>
                            <textarea name="isi" class="w-full border p-2 rounded text-sm h-32 outline-none focus:ring-2 focus:ring-indigo-500" placeholder="Tulis artikel di sini..." required></textarea>
                        </div>
                        <div class="mb-4">
                            <label class="block text-xs font-bold mb-1 text-gray-600">Gambar Banner (Wajib)</label>
                            <input type="file" name="gambar" class="w-full text-xs" accept="image/*" required>
                        </div>
                        <div class="flex justify-end gap-2">
                            <button type="button" onclick="tutupModal()" class="bg-gray-400 text-white px-4 py-2 rounded text-sm">Batal</button>
                            <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded text-sm font-bold hover:bg-indigo-700">Terbitkan Artikel</button>
                        </div>
                    </form>`;
                tampilModal("Buat Artikel Baru", html);
            } catch (error) {
                tampilModal("Error", "<p class='text-red-500 p-5'>Gagal mengambil data dari database. Pastikan koneksi aman.</p>");
            }
        }

        // =====================================================
        // 6. SIMPAN DATA (Tambah)
        // =====================================================
        function prosesSimpan(tipe) {
            const formElement = document.getElementById('form-' + tipe);
            const fd = new FormData(formElement);

            fetch(`simpan_${tipe}.php`, { method: 'POST', body: fd })
                .then(res => res.text())
                .then(msg => {
                    alert(msg);
                    tutupModal();
                    loadMenu(tipe);
                })
                .catch(err => alert("Terjadi kesalahan: " + err));
        }

        // =====================================================
        // 7. HAPUS DATA
        // =====================================================
        function hapusData(tipe, id) {
            const modalHapus = document.getElementById('modal-hapus');
            if (modalHapus) {
                modalHapus.classList.remove('hidden');
                const btnYa = document.getElementById('btn-konfirmasi-hapus');
                btnYa.onclick = function () {
                    const fd = new FormData();
                    fd.append('id', id);
                    fetch(`hapus_${tipe}.php`, { method: 'POST', body: fd })
                        .then(res => res.text())
                        .then(msg => {
                            alert(msg);
                            tutupModalHapus();
                            loadMenu(tipe);
                        })
                        .catch(err => console.error("Error:", err));
                };
            }
        }

        // =====================================================
        // 8. EDIT DATA (Penulis / Artikel / Kategori)
        // =====================================================
        async function editData(tipe, id) {
            tampilModal("Edit " + tipe, "<p class='p-5 text-center'>Loading data...</p>");

            try {
                if (tipe === 'penulis') {
                    const res  = await fetch(`ambil_satu_penulis.php?id=${id}`);
                    const data = await res.json();

                    const html = `
                        <form id="form-edit-penulis" onsubmit="event.preventDefault(); prosesUpdate('penulis', ${id});">
                            <div class="grid grid-cols-2 gap-4 mb-3">
                                <div>
                                    <label class="block text-xs font-bold mb-1">Nama Depan</label>
                                    <input type="text" name="nama_depan" value="${data.nama_depan}" class="w-full border p-2 rounded text-sm" required>
                                </div>
                                <div>
                                    <label class="block text-xs font-bold mb-1">Nama Belakang</label>
                                    <input type="text" name="nama_belakang" value="${data.nama_belakang}" class="w-full border p-2 rounded text-sm" required>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label class="block text-xs font-bold mb-1">Username</label>
                                <input type="text" name="user_name" value="${data.user_name}" class="w-full border p-2 rounded text-sm" required>
                            </div>
                            <div class="mb-3">
                                <label class="block text-[10px] font-bold mb-1 text-amber-600">Password Baru (Kosongkan jika tidak ingin ganti)</label>
                                <input type="password" name="password" class="w-full border p-2 rounded text-sm" placeholder="Ketik password baru jika ingin ganti">
                            </div>
                            <div class="mb-4">
                                <label class="block text-[10px] font-bold mb-1">Foto Profil Baru (Kosongkan jika tetap)</label>
                                <input type="file" name="foto" class="w-full text-xs">
                            </div>
                            <div class="flex justify-end gap-2">
                                <button type="button" onclick="tutupModal()" class="bg-gray-400 text-white px-4 py-2 rounded text-xs font-bold">Batal</button>
                                <button type="submit" class="bg-amber-600 text-white px-4 py-2 rounded text-xs font-bold shadow-md">Update Data</button>
                            </div>
                        </form>`;
                    tampilModal("Edit Data Penulis", html);

                } else if (tipe === 'artikel') {
                    const [resArt, resPen, resKat] = await Promise.all([
                        fetch(`ambil_satu_artikel.php?tipe=artikel&id=${id}`).then(r => r.json()),
                        fetch('ambil_penulis.php?format=json').then(r => r.json()),
                        fetch('ambil_kategori.php?format=json').then(r => r.json())
                    ]);

                    const optP = resPen.map(p => `<option value="${p.id}" ${p.id == resArt.id_penulis ? 'selected' : ''}>${p.nama_depan} ${p.nama_belakang}</option>`).join('');
                    const optK = resKat.map(k => `<option value="${k.id}" ${k.id == resArt.id_kategori ? 'selected' : ''}>${k.nama_kategori}</option>`).join('');

                    const html = `
                        <form id="form-edit-artikel" onsubmit="event.preventDefault(); prosesUpdate('artikel', ${id});">
                            <div class="mb-3 text-left">
                                <label class="block text-[10px] font-bold mb-1 uppercase">Judul Artikel</label>
                                <input type="text" name="judul" value="${resArt.judul}" class="w-full border p-2 rounded text-sm outline-none focus:ring-1 focus:ring-blue-500" required>
                            </div>
                            <div class="grid grid-cols-2 gap-3 mb-3 text-left">
                                <div>
                                    <label class="block text-[10px] font-bold mb-1 uppercase">Penulis</label>
                                    <select name="id_penulis" class="w-full border p-2 rounded text-sm">${optP}</select>
                                </div>
                                <div>
                                    <label class="block text-[10px] font-bold mb-1 uppercase">Kategori</label>
                                    <select name="id_kategori" class="w-full border p-2 rounded text-sm">${optK}</select>
                                </div>
                            </div>
                            <div class="mb-3 text-left">
                                <label class="block text-[10px] font-bold mb-1 uppercase">Isi Konten</label>
                                <textarea name="isi" class="w-full border p-2 rounded text-sm h-32" required>${resArt.isi}</textarea>
                            </div>
                            <div class="mb-4 text-left">
                                <label class="block text-[10px] font-bold mb-1 uppercase">Ganti Gambar (Kosongkan jika tetap)</label>
                                <input type="file" name="gambar" class="w-full text-xs">
                            </div>
                            <div class="flex justify-end gap-2">
                                <button type="button" onclick="tutupModal()" class="bg-gray-400 text-white px-4 py-2 rounded text-xs font-bold">Batal</button>
                                <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded text-xs font-bold">Update Artikel</button>
                            </div>
                        </form>`;
                    tampilModal("Edit Artikel", html);

   }   else if (tipe === 'kategori') {
            const res = await fetch(`ambil_satu_kategori.php?id=${id}`);
            const data = await res.json();
            
            const daftarUmum = ["Teknologi", "Pendidikan", "Kesehatan", "Hiburan", "Olahraga"];
            const isLainnya = !daftarUmum.includes(data.nama_kategori);

            // Buat opsi dropdown secara manual agar tidak merusak template literal
            let opsiHtml = '<option value="">-- Pilih Kategori Umum --</option>';
            daftarUmum.forEach(k => {
                const selected = (data.nama_kategori === k) ? 'selected' : '';
                opsiHtml += `<option value="${k}" ${selected}>${k}</option>`;
            });
            const selectedLainnya = isLainnya ? 'selected' : '';
            opsiHtml += `<option value="Lainnya" ${selectedLainnya}>Kategori Lainnya (Ketik Manual)</option>`;

            const htmlForm = `
                <form id="form-edit-kategori" onsubmit="event.preventDefault(); prosesUpdate('kategori', ${id});">
                    <div class="mb-4 text-left">
                        <label class="block text-sm font-bold mb-2 text-gray-700">Pilih Kategori Artikel</label>
                        <select onchange="cekKategoriLainnyaEdit(this.value)" class="w-full border p-2 rounded mb-2 outline-none focus:ring-2 focus:ring-green-500">
                            ${opsiHtml}
                        </select>
                        <input type="text" id="edit_input_manual" name="nama_kategori" value="${data.nama_kategori}" 
                               class="${isLainnya ? '' : 'hidden'} w-full border p-2 rounded outline-none focus:ring-2 focus:ring-green-500" required>
                    </div>
                    <div class="mb-4 text-left">
                        <label class="block text-sm font-bold mb-2 text-gray-700">Keterangan</label>
                        <textarea name="keterangan" class="w-full border p-2 rounded h-24 outline-none focus:ring-2 focus:ring-green-500">${data.keterangan || ''}</textarea>
                    </div>
                    <div class="flex justify-end gap-2">
                        <button type="button" onclick="tutupModal()" class="bg-gray-400 text-white px-4 py-2 rounded text-sm font-bold">Batal</button>
                        <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded text-sm font-bold shadow-md">Update Kategori</button>
                    </div>
                </form>`;
            tampilModal("Edit Kategori", htmlForm);
        }
            } catch (e) {
                console.error(e);
                tampilModal("Error", "<p class='text-red-500 p-5'>Gagal memuat data. Cek konsol untuk detail.</p>");
            }
        }

        // =====================================================
        // 9. UPDATE DATA (Simpan Edit)
        // =====================================================
        function prosesUpdate(tipe, id) {
            const formId = 'form-edit-' + tipe;
            const formEl = document.getElementById('form-edit-' + tipe);
            if (!formEl) { alert('Form tidak ditemukan!'); return; }

            const fd = new FormData(formEl);
            fd.append('id', id);

            fetch(`update_${tipe}.php`, { method: 'POST', body: fd })
                .then(res => res.text())
                .then(msg => {
                    alert(msg);
                    tutupModal();
                    loadMenu(tipe);
                })
                .catch(err => alert("Gagal update: " + err));
        }
    </script>
</body>
</html>