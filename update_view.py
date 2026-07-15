import os

file_path = 'app/Views/Admin/PackageAndPrintOption/PackageAndPrintOptionManajemen.php'
with open(file_path, 'r', encoding='utf-8') as f:
    content = f.read()

# 1. Add sort_pkg to Package search form
old_pkg_search = """                        <div class="relative flex-1">
                            <span class="material-symbols-outlined absolute left-3 top-2.5 text-slate-400 text-[20px]">search</span>
                            <input name="q" value="<?= esc($keyword ?? '') ?>"
                                   class="w-full pl-10 pr-4 py-2 border border-slate-200 rounded-lg text-sm focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition-all"
                                   placeholder="Cari ID atau Nama Paket..." type="text"/>
                        </div>"""
new_pkg_search = """                        <div class="relative flex-1">
                            <span class="material-symbols-outlined absolute left-3 top-2.5 text-slate-400 text-[20px]">search</span>
                            <input name="q" value="<?= esc($keyword ?? '') ?>"
                                   class="w-full pl-10 pr-4 py-2 border border-slate-200 rounded-lg text-sm focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition-all"
                                   placeholder="Cari ID atau Nama Paket..." type="text"/>
                        </div>
                        <div class="w-48 relative">
                            <span class="material-symbols-outlined absolute left-3 top-2.5 text-slate-400 text-[20px]">sort</span>
                            <select name="sort_pkg" onchange="this.form.submit()" class="w-full pl-10 pr-8 py-2 border border-slate-200 rounded-lg text-sm appearance-none outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary bg-white cursor-pointer">
                                <option value="terbaru" <?= (isset($sortPkg) && $sortPkg == 'terbaru') ? 'selected' : '' ?>>Terbaru</option>
                                <option value="termurah" <?= (isset($sortPkg) && $sortPkg == 'termurah') ? 'selected' : '' ?>>Harga Terendah</option>
                                <option value="termahal" <?= (isset($sortPkg) && $sortPkg == 'termahal') ? 'selected' : '' ?>>Harga Tertinggi</option>
                            </select>
                        </div>"""
content = content.replace(old_pkg_search, new_pkg_search)


# 2. Add sort_opt to Daftar Cetak
old_opt_header = """                <div class="p-6 border-b border-slate-100 flex justify-between items-center bg-white">
                    <div class="flex items-center gap-3">
                        <div class="w-1 h-6 bg-blue-900 rounded-full"></div>
                        <h4 class="font-h2 text-h2 text-on-surface">Daftar Cetak</h4>
                    </div>
                    <a href="<?= base_url('admin/print-options/create') ?>"
                       class="bg-blue-900 text-white px-4 py-2 rounded-lg text-sm font-bold flex items-center gap-2 hover:bg-blue-800 transition-colors shadow-sm whitespace-nowrap">
                        <span class="material-symbols-outlined text-[18px]">print</span>
                        Tambah Cetak
                    </a>
                </div>"""
new_opt_header = """                <div class="p-6 border-b border-slate-100 flex justify-between items-center bg-white">
                    <div class="flex items-center gap-3">
                        <div class="w-1 h-6 bg-blue-900 rounded-full"></div>
                        <h4 class="font-h2 text-h2 text-on-surface">Daftar Cetak</h4>
                    </div>
                    <a href="<?= base_url('admin/print-options/create') ?>"
                       class="bg-blue-900 text-white px-4 py-2 rounded-lg text-sm font-bold flex items-center gap-2 hover:bg-blue-800 transition-colors shadow-sm whitespace-nowrap">
                        <span class="material-symbols-outlined text-[18px]">print</span>
                        Tambah Cetak
                    </a>
                </div>
                <div class="p-6 bg-white">
                    <form method="get" action="<?= base_url('admin/packages') ?>" class="w-full relative">
                        <!-- Keep existing pkg search/sort so we don't lose it -->
                        <input type="hidden" name="q" value="<?= esc($keyword ?? '') ?>">
                        <input type="hidden" name="sort_pkg" value="<?= esc($sortPkg ?? 'terbaru') ?>">
                        <input type="hidden" name="page" value="<?= esc($currentPkgPage ?? 1) ?>">
                        
                        <span class="material-symbols-outlined absolute left-3 top-2.5 text-slate-400 text-[20px]">sort</span>
                        <select name="sort_opt" onchange="this.form.submit()" class="w-full pl-10 pr-8 py-2 border border-slate-200 rounded-lg text-sm appearance-none outline-none focus:ring-2 focus:ring-blue-900/20 focus:border-blue-900 bg-white cursor-pointer">
                            <option value="termurah" <?= (isset($sortOpt) && $sortOpt == 'termurah') ? 'selected' : '' ?>>Harga Terendah</option>
                            <option value="termahal" <?= (isset($sortOpt) && $sortOpt == 'termahal') ? 'selected' : '' ?>>Harga Tertinggi</option>
                        </select>
                    </form>
                </div>"""
content = content.replace(old_opt_header, new_opt_header)


# 3. Add Pagination to Daftar Cetak, replace Kelola Detail Opsi
old_opt_footer = """                <div class="p-4 bg-slate-50 border-t border-slate-100 text-center">
                    <a href="<?= base_url('admin/print-options') ?>" class="text-xs font-bold text-tertiary hover:text-purple-800 transition-colors flex items-center justify-center gap-1">
                        Kelola Detail Opsi <span class="material-symbols-outlined text-[16px]">arrow_forward</span>
                    </a>
                </div>"""
# Sometimes it might have been modified to bg-blue-900 but let's check text-tertiary
if 'Kelola Detail Opsi' in content:
    import re
    # We will just replace the whole div block containing Kelola Detail Opsi
    content = re.sub(
        r'<div class="p-4 bg-slate-50 border-t border-slate-100 text-center">.*?Kelola Detail Opsi.*?</div>',
        """                <!-- Pagination Cetak -->
                <div class="mt-auto p-6 bg-white text-slate-500 text-xs flex justify-between items-center border-t border-slate-100">
                    <span>Menampilkan <?= count($printOptions ?? []) ?> dari <?= $totalPrintOptions ?? 0 ?> opsi</span>
                    <div class="flex gap-2">
                        <a href="<?= base_url('admin/packages?opt_page=' . max(1, ($currentOptPage ?? 1) - 1) . '&sort_opt=' . ($sortOpt ?? '') . '&page=' . ($currentPkgPage ?? 1) . '&sort_pkg=' . ($sortPkg ?? '') . '&q=' . ($keyword ?? '')) ?>"
                           class="w-8 h-8 flex items-center justify-center border border-slate-200 bg-white rounded-lg hover:bg-slate-50 transition-colors text-slate-400 <?= ($currentOptPage ?? 1) <= 1 ? 'opacity-40 pointer-events-none' : '' ?>">
                            <span class="material-symbols-outlined text-sm">chevron_left</span>
                        </a>
                        <a href="<?= base_url('admin/packages?opt_page=' . min(($totalOptPages ?? 1), ($currentOptPage ?? 1) + 1) . '&sort_opt=' . ($sortOpt ?? '') . '&page=' . ($currentPkgPage ?? 1) . '&sort_pkg=' . ($sortPkg ?? '') . '&q=' . ($keyword ?? '')) ?>"
                           class="w-8 h-8 flex items-center justify-center border border-slate-200 bg-white rounded-lg hover:bg-slate-50 transition-colors text-slate-400 <?= ($currentOptPage ?? 1) >= ($totalOptPages ?? 1) ? 'opacity-40 pointer-events-none' : '' ?>">
                            <span class="material-symbols-outlined text-sm">chevron_right</span>
                        </a>
                    </div>
                </div>""",
        content,
        flags=re.DOTALL
    )

# Also ensure pkg pagination links preserve the opt_page and sort parameters
content = content.replace(
    "base_url('admin/packages?page=' . max(1, ($currentPkgPage ?? 1) - 1) . '&q=' . ($keyword ?? ''))",
    "base_url('admin/packages?page=' . max(1, ($currentPkgPage ?? 1) - 1) . '&sort_pkg=' . ($sortPkg ?? '') . '&opt_page=' . ($currentOptPage ?? 1) . '&sort_opt=' . ($sortOpt ?? '') . '&q=' . ($keyword ?? ''))"
)
content = content.replace(
    "base_url('admin/packages?page=' . $i . '&q=' . ($keyword ?? ''))",
    "base_url('admin/packages?page=' . $i . '&sort_pkg=' . ($sortPkg ?? '') . '&opt_page=' . ($currentOptPage ?? 1) . '&sort_opt=' . ($sortOpt ?? '') . '&q=' . ($keyword ?? ''))"
)
content = content.replace(
    "base_url('admin/packages?page=' . min(($totalPkgPages ?? 1), ($currentPkgPage ?? 1) + 1) . '&q=' . ($keyword ?? ''))",
    "base_url('admin/packages?page=' . min(($totalPkgPages ?? 1), ($currentPkgPage ?? 1) + 1) . '&sort_pkg=' . ($sortPkg ?? '') . '&opt_page=' . ($currentOptPage ?? 1) . '&sort_opt=' . ($sortOpt ?? '') . '&q=' . ($keyword ?? ''))"
)

# 4. Add onclick to "Buat Campaign"
content = content.replace(
    '<button class="mt-8 bg-white text-blue-700 font-label-bold text-label-bold py-2 rounded shadow-lg hover:bg-slate-50 transition-colors">',
    '<button onclick="alert(\'Fitur Buat Campaign akan datang pada pembaruan berikutnya!\')" class="mt-8 bg-white text-blue-700 font-label-bold text-label-bold py-2 rounded shadow-lg hover:bg-slate-50 transition-colors">'
)

with open(file_path, 'w', encoding='utf-8') as f:
    f.write(content)
print("SUCCESS")
