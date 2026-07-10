import os
import re

# 1. Update PackageAndPrintOptionManajemen.php
file_path = 'app/Views/Admin/PackageAndPrintOption/PackageAndPrintOptionManajemen.php'
with open(file_path, 'r', encoding='utf-8') as f:
    content = f.read()

# Ubah ikon add_circle ke inventory_2 untuk Tambah Paket Layanan
content = content.replace(
    '<span class="material-symbols-outlined text-[18px]">add_circle</span>\n                            Tambah Paket Layanan',
    '<span class="material-symbols-outlined text-[18px]">inventory_2</span>\n                            Tambah Paket Layanan'
)
content = content.replace(
    '<span class="material-symbols-outlined text-[20px]">add_circle</span>\n                            Tambah Paket Layanan',
    '<span class="material-symbols-outlined text-[18px]">inventory_2</span>\n                            Tambah Paket Layanan'
)

# Ubah ikon add_circle ke print untuk Tambah Cetak
content = content.replace(
    '<span class="material-symbols-outlined text-[18px]">add_circle</span>\n                        Tambah Cetak',
    '<span class="material-symbols-outlined text-[18px]">print</span>\n                        Tambah Cetak'
)
content = content.replace(
    '<span class="material-symbols-outlined text-[20px]">add_circle</span>\n                        Tambah Cetak',
    '<span class="material-symbols-outlined text-[18px]">print</span>\n                        Tambah Cetak'
)

# Ubah warna pada table Daftar Cetak menjadi navy
# Title line
content = content.replace('<div class="w-1 h-6 bg-tertiary rounded-full"></div>', '<div class="w-1 h-6 bg-blue-900 rounded-full"></div>')
# Button Tambah Cetak
content = content.replace('bg-tertiary text-white', 'bg-blue-900 text-white')
content = content.replace('hover:bg-purple-800', 'hover:bg-blue-800')

# Pada ikon AKSI ubah supaya tidak ada background
# Before: text-blue-600 bg-blue-50 hover:bg-blue-100
# After: text-slate-400 hover:text-blue-600 hover:bg-blue-50
content = content.replace('text-blue-600 bg-blue-50 hover:bg-blue-100', 'text-slate-400 hover:text-blue-600 hover:bg-blue-50')
content = content.replace('text-red-600 bg-red-50 hover:bg-red-100', 'text-slate-400 hover:text-red-600 hover:bg-red-50')

# Hapus Paket Cepat (sudah dihapus di step sebelumnya tapi just in case)
content = re.sub(r'<div class="fixed bottom-8 right-8 z-\[60\]">.*?</div>', '', content, flags=re.DOTALL)

# Delete Modal: update text and button action to universal format
content = content.replace("openDeleteModal('<?= base_url('admin/packages/' . $pkg['id'] . '/delete') ?>')", "openUniversalDeleteModal('<?= base_url('admin/packages/' . $pkg['id'] . '/delete') ?>', '<?= esc($pkg['name'], 'js') ?>', 'Hapus Paket?')")
content = content.replace("openDeleteModal('<?= base_url('admin/print-options/' . $opt['id'] . '/delete') ?>')", "openUniversalDeleteModal('<?= base_url('admin/print-options/' . $opt['id'] . '/delete') ?>', '<?= esc($opt['name'] ?? 'Opsi', 'js') ?>', 'Hapus Opsi Cetak?')")

# Include universal delete modal JS logic if not already present
if 'function openUniversalDeleteModal' not in content:
    content = content.replace(
        'function openDeleteModal(actionUrl) {',
        'function openUniversalDeleteModal(actionUrl, itemName, titleText = "Konfirmasi Hapus") {\n        const msg = document.getElementById("deleteConfirmMsg");\n        if(msg) msg.innerHTML = `Apakah Anda yakin ingin menghapus <b>${itemName}</b>?<br>Tindakan ini tidak dapat dibatalkan.`;\n        const t = document.getElementById("deleteConfirmTitle");\n        if(t) t.innerText = titleText;\n        deleteForm.action = actionUrl;\n        deleteModal.classList.remove("hidden");\n        deleteModal.classList.add("flex");\n        void deleteModal.offsetWidth;\n        deleteModal.classList.remove("opacity-0");\n        deleteModalContent.classList.remove("scale-95");\n        deleteModalContent.classList.add("scale-100");\n    }\n    function openDeleteModal(actionUrl) {'
    )
    content = content.replace(
        'Apakah Anda yakin ingin menghapus data ini?<br>Tindakan ini tidak dapat dibatalkan.',
        '<span id="deleteConfirmMsg">Apakah Anda yakin ingin menghapus data ini?<br>Tindakan ini tidak dapat dibatalkan.</span>'
    )
    content = content.replace(
        '<h3 class="text-2xl font-black text-slate-900 text-center mb-4">Konfirmasi Hapus</h3>',
        '<h3 id="deleteConfirmTitle" class="text-2xl font-black text-slate-900 text-center mb-4">Konfirmasi Hapus</h3>'
    )

with open(file_path, 'w', encoding='utf-8') as f:
    f.write(content)


# 2. Update PackageForm.php
file_path = 'app/Views/Admin/PackageAndPrintOption/PackageForm.php'
with open(file_path, 'r', encoding='utf-8') as f:
    content = f.read()

# Maximize grid
content = content.replace('max-w-4xl', 'max-w-full')
# Action route
content = content.replace("base_url('admin/packages/store')", "base_url('admin/packages')")
# Icon instead of bar
content = content.replace('<div class="w-1 h-6 bg-primary rounded-full"></div>', '<span class="material-symbols-outlined text-[24px] text-primary">inventory_2</span>')

# Add Title and description above the card
header_html = """
        <div class="mb-6">
            <h2 class="font-h1 text-h1 text-on-surface"><?= isset($package) ? 'Edit Paket' : 'Tambah Paket Baru' ?></h2>
            <p class="font-body-sm text-body-sm text-on-surface-variant">Ubah pengaturan dan detail operasional paket layanan.</p>
        </div>
        <div class="bg-white border border-slate-200 rounded-xl shadow-sm overflow-hidden">
"""
content = content.replace('<div class="bg-white border border-slate-200 rounded-xl shadow-sm overflow-hidden">', header_html)
content = content.replace('<h4 class="font-h2 text-h2 text-slate-800"><?= isset($package) ? \'Edit Paket\' : \'Tambah Paket Baru\' ?></h4>', '<h4 class="font-h2 text-h2 text-slate-800">Konfigurasi Paket Layanan</h4>')

with open(file_path, 'w', encoding='utf-8') as f:
    f.write(content)


# 3. Update PrintOptionForm.php
file_path = 'app/Views/Admin/PackageAndPrintOption/PrintOptionForm.php'
with open(file_path, 'r', encoding='utf-8') as f:
    content = f.read()

# Maximize grid
content = content.replace('max-w-4xl', 'max-w-full')
# Action route
content = content.replace("base_url('admin/print-options/store')", "base_url('admin/print-options')")
# Icon instead of bar
content = content.replace('<div class="w-1 h-6 bg-tertiary rounded-full"></div>', '<span class="material-symbols-outlined text-[24px] text-blue-900">print</span>')

# Add Title and description above the card
header_html = """
        <div class="mb-6">
            <h2 class="font-h1 text-h1 text-on-surface"><?= isset($option) ? 'Edit Opsi Cetak' : 'Tambah Opsi Cetak' ?></h2>
            <p class="font-body-sm text-body-sm text-on-surface-variant">Ubah pengaturan dan harga tambahan untuk opsi cetak.</p>
        </div>
        <div class="bg-white border border-slate-200 rounded-xl shadow-sm overflow-hidden">
"""
content = content.replace('<div class="bg-white border border-slate-200 rounded-xl shadow-sm overflow-hidden">', header_html)
content = content.replace('<h4 class="font-h2 text-h2 text-slate-800"><?= isset($option) ? \'Edit Opsi Cetak\' : \'Tambah Opsi Cetak\' ?></h4>', '<h4 class="font-h2 text-h2 text-slate-800">Konfigurasi Opsi Cetak</h4>')

# Navy save button
content = content.replace('bg-tertiary', 'bg-blue-900')
content = content.replace('hover:bg-purple-800', 'hover:bg-blue-800')

with open(file_path, 'w', encoding='utf-8') as f:
    f.write(content)


# 4. Update PackageAndPrintOptionController.php
file_path = 'app/Controllers/Admin/PackageAndPrintOptionController.php'
with open(file_path, 'r', encoding='utf-8') as f:
    content = f.read()

# Pagination max 10
# Before: $packages = $this->packageModel->like('id', $keyword)->orLike('name', $keyword)->paginate(5, 'packages');
# After: paginate(10, 'packages')
content = re.sub(r'paginate\(\s*\d+\s*,\s*\'packages\'\)', "paginate(10, 'packages')", content)
content = re.sub(r'paginate\(\s*\d+\s*,\s*\'print_options\'\)', "paginate(10, 'print_options')", content)
# Also change any custom pagination limit if it's set manually
content = re.sub(r'\$perPage\s*=\s*\d+;', '$perPage = 10;', content)
content = re.sub(r'limit\(\s*\d+\s*\)', 'limit(10)', content)

# Fix PrintOptionManajemen error (redirect to packages)
content = content.replace("return view('Admin/PackageAndPrintOption/PrintOptionManajemen', [", "return redirect()->to(base_url('admin/packages')); //")

# Export Laporan (Kolom 1: LAPORAN PAKET & CETAK, then export both tables)
old_export = """    public function exportPackages()
    {
        $packages = $this->packageModel->findAll();

        $filename = 'Laporan_Paket_' . date('Ymd_His') . '.csv';

        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="' . $filename . '"');

        $output = fopen('php://output', 'w');
        fputcsv($output, ['ID PAKET', 'NAMA PAKET', 'JUMLAH FOTO', 'DURASI', 'HARGA'], ';');

        foreach ($packages as $p) {
            fputcsv($output, [
                $p['id'], $p['name'], $p['photo_count'],
                $p['duration'], $p['base_price'],
            ], ';');
        }
        fclose($output);
        exit;
    }"""
new_export = """    public function exportPackages()
    {
        $packages = $this->packageModel->findAll();
        $printOptions = $this->printOptionModel->findAll();

        $filename = 'Laporan_Paket_Cetak_' . date('Ymd_His') . '.csv';

        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="' . $filename . '"');

        $output = fopen('php://output', 'w');
        
        fputcsv($output, ['LAPORAN PAKET & CETAK', '', '', '', ''], ';');
        fputcsv($output, ['', '', '', '', ''], ';');
        
        // Export Packages
        fputcsv($output, ['DAFTAR PAKET LAYANAN', '', '', '', ''], ';');
        fputcsv($output, ['ID PAKET', 'NAMA PAKET', 'JUMLAH FOTO', 'DURASI (Menit)', 'HARGA (Rp)'], ';');

        foreach ($packages as $p) {
            fputcsv($output, [
                $p['id'], $p['name'], $p['photo_count'],
                $p['duration'], $p['base_price'],
            ], ';');
        }
        
        fputcsv($output, ['', '', '', '', ''], ';');
        
        // Export Print Options
        fputcsv($output, ['DAFTAR CETAK', '', '', '', ''], ';');
        fputcsv($output, ['ID OPSI', 'NAMA OPSI', 'JUMLAH COPIES', 'HARGA TAMBAHAN (Rp)', ''], ';');
        foreach ($printOptions as $opt) {
            $name = isset($opt['name']) ? $opt['name'] : 'Opsi Cetak';
            fputcsv($output, [
                $opt['id'], $name, $opt['copies'],
                $opt['extra_price'], ''
            ], ';');
        }

        fclose($output);
        exit;
    }"""
content = content.replace(old_export, new_export)

with open(file_path, 'w', encoding='utf-8') as f:
    f.write(content)

print("SUCCESS")
