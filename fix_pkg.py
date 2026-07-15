import os

file_path = 'app/Views/Admin/PackageAndPrintOption/PackageAndPrintOptionManajemen.php'
with open(file_path, 'r', encoding='utf-8') as f:
    content = f.read()

# 1. Sesuaikan ukuran font 4 grid
content = content.replace('text-3xl font-black', 'text-2xl font-black')

# 2. Ubah logika ikon AKSI sehingga selalu muncul
content = content.replace('opacity-0 group-hover:opacity-100 transition-opacity', '')

# 3. Pindahkan button "+ Tambah Paket Baru" ke header table
# First, remove it from the top Hero Header
content = content.replace("""                <a href="<?= base_url('admin/packages/create') ?>"
                   class="flex items-center gap-2 bg-primary text-on-primary px-4 py-2 rounded font-label-bold text-label-bold hover:opacity-90 transition-opacity">
                    <span class="material-symbols-outlined text-[18px]">add_circle</span>
                    Tambah Paket Baru
                </a>""", "")

# Then, replace the Daftar Paket Layanan header
old_pkg_header = """                <div class="p-6 border-b border-slate-100 bg-white">
                    <div class="flex items-center gap-3 mb-6">
                        <div class="w-1 h-6 bg-primary rounded-full"></div>
                        <h4 class="font-h2 text-h2 text-on-surface">Daftar Paket Layanan</h4>
                    </div>"""
new_pkg_header = """                <div class="p-6 border-b border-slate-100 bg-white">
                    <div class="flex justify-between items-center mb-6">
                        <div class="flex items-center gap-3">
                            <div class="w-1 h-6 bg-primary rounded-full"></div>
                            <h4 class="font-h2 text-h2 text-on-surface">Daftar Paket Layanan</h4>
                        </div>
                        <a href="<?= base_url('admin/packages/create') ?>" class="bg-primary text-white px-4 py-2 rounded-lg text-sm font-bold flex items-center gap-2 hover:bg-blue-800 transition-colors shadow-sm">
                            <span class="material-symbols-outlined text-[18px]">add_circle</span>
                            Tambah Paket Layanan
                        </a>
                    </div>"""
content = content.replace(old_pkg_header, new_pkg_header)

# 5. Ubah teks "Opsi Cetak" menjadi "Daftar Cetak"
# Make sure we only change the title
content = content.replace('<h4 class="font-h2 text-h2 text-on-surface">Opsi Cetak</h4>', '<h4 class="font-h2 text-h2 text-on-surface">Daftar Cetak</h4>')

# 6. Ubah button + pada grid Opsi Cetak
old_print_btn = """                    <a href="<?= base_url('admin/print-options/create') ?>"
                       class="text-primary hover:bg-blue-50 p-1.5 rounded-lg transition-colors" title="Tambah Opsi Cetak">
                        <span class="material-symbols-outlined text-[20px]">add</span>
                    </a>"""
new_print_btn = """                    <a href="<?= base_url('admin/print-options/create') ?>"
                       class="bg-tertiary text-white px-4 py-2 rounded-lg text-sm font-bold flex items-center gap-2 hover:bg-purple-800 transition-colors shadow-sm whitespace-nowrap">
                        <span class="material-symbols-outlined text-[18px]">add_circle</span>
                        Tambah Cetak
                    </a>"""
content = content.replace(old_print_btn, new_print_btn)

# 9. Pagenation paling bawah (make container h-full and pagination mt-auto)
# Actually the container already has flex-col. So we just add h-full to col-span-8 and mt-auto to pagination
content = content.replace('col-span-8 bg-white border border-slate-200 rounded-xl shadow-sm overflow-hidden flex flex-col', 'col-span-8 bg-white border border-slate-200 rounded-xl shadow-sm overflow-hidden flex flex-col h-full')
content = content.replace('<div class="p-6 bg-white text-slate-500 text-xs flex justify-between items-center border-t border-slate-100">', '<div class="mt-auto p-6 bg-white text-slate-500 text-xs flex justify-between items-center border-t border-slate-100">')

# 10. Sesuaikan layout table
# The search form for package doesn't have border-b, and the table has border-y. It's mostly correct, but let's make sure it matches.
# Wait, the search bar currently is inside the same p-6 div as the header!
# <div class="p-6 border-b border-slate-100 bg-white"> contains BOTH title and search form!
# We should split them to match the standard:
# Title in one div with border-b, Search in another div.
import re
# We need to split the p-6 block for packages.
# Let's do this carefully.
old_search = """                    <form method="get" action="<?= base_url('admin/packages') ?>" class="flex gap-4">
                        <div class="relative flex-1">
                            <span class="material-symbols-outlined absolute left-3 top-2.5 text-[18px] text-slate-400">search</span>
                            <input name="q" value="<?= esc($keyword ?? '') ?>"
                                   class="w-full pl-9 pr-4 py-2 border border-slate-200 rounded-lg text-sm focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition-all"
                                   placeholder="Cari ID atau Nama Paket..." type="text"/>
                        </div>
                        <?php if (!empty($keyword)): ?>
                            <a href="<?= base_url('admin/packages') ?>" class="bg-slate-100 hover:bg-slate-200 text-slate-600 px-4 py-2 rounded-lg text-sm font-medium transition-colors flex items-center">
                                Reset
                            </a>
                        <?php endif; ?>
                    </form>
                </div>"""
new_search = """                </div>
                <div class="p-6 bg-white flex flex-col sm:flex-row gap-3">
                    <form method="get" action="<?= base_url('admin/packages') ?>" class="flex flex-col sm:flex-row flex-1 gap-3 w-full">
                        <div class="relative flex-1">
                            <span class="material-symbols-outlined absolute left-3 top-2.5 text-slate-400 text-[20px]">search</span>
                            <input name="q" value="<?= esc($keyword ?? '') ?>"
                                   class="w-full pl-10 pr-4 py-2 border border-slate-200 rounded-lg text-sm focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition-all"
                                   placeholder="Cari ID atau Nama Paket..." type="text"/>
                        </div>
                        <?php if (!empty($keyword)): ?>
                            <a href="<?= base_url('admin/packages') ?>" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 text-sm font-semibold rounded-lg flex items-center transition-colors">
                                Reset
                            </a>
                        <?php endif; ?>
                    </form>
                </div>"""
content = content.replace(old_search, new_search)

# Ensure the thead has border-y
content = content.replace('class="bg-slate-50 text-slate-500 font-label-bold text-xs uppercase tracking-wider border-y border-slate-100"', 'class="border-y border-slate-100 bg-white text-slate-500 text-xs font-bold uppercase tracking-wider"')
content = content.replace('class="bg-slate-50 text-slate-500 font-label-bold text-xs uppercase tracking-wider sticky top-0 border-y border-slate-100"', 'class="border-y border-slate-100 bg-white text-slate-500 text-xs font-bold uppercase tracking-wider sticky top-0"')

# Ensure the table wrapper doesn't have pt-0 if it doesn't need to
content = content.replace('<div class="overflow-x-auto p-6 pt-0">', '<div class="overflow-x-auto">')
content = content.replace('<table class="w-full text-left border-collapse mt-4">', '<table class="w-full text-left border-collapse">')
content = content.replace('<div class="flex-1 overflow-y-auto max-h-[440px] p-6 pt-0">', '<div class="flex-1 overflow-y-auto max-h-[440px]">')

# 11. Hilangkan fitur + yang mengambang
floating_btn = """<div class="fixed bottom-8 right-8 z-[60]">
    <a href="<?= base_url('admin/packages/create') ?>"
       class="bg-primary-container text-white w-14 h-14 rounded-full shadow-xl flex items-center justify-center hover:scale-105 transition-transform active:scale-95 group relative">
        <span class="material-symbols-outlined text-3xl">add</span>
        <span class="absolute right-full mr-4 bg-slate-800 text-white text-[10px] py-1 px-3 rounded whitespace-nowrap opacity-0 group-hover:opacity-100 transition-opacity">Paket Cepat</span>
    </a>
</div>"""
content = content.replace(floating_btn, "")

with open(file_path, 'w', encoding='utf-8') as f:
    f.write(content)

print("SUCCESS")
