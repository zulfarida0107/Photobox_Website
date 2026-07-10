import os

# 1. Fix BoothManajemen.php layout
file_path = 'app/Views/Admin/Booth/BoothManajemen.php'
with open(file_path, 'r', encoding='utf-8') as f:
    content = f.read()

# Fix the tbody background
content = content.replace('<tbody class="divide-y divide-outline-variant">', '<tbody class="divide-y divide-slate-50 bg-white">')

# Fix the pagination background
content = content.replace('bg-slate-50 border-t border-slate-100 flex items-center', 'bg-white border-t border-slate-100 flex items-center')
content = content.replace('bg-slate-50 rounded-b-2xl border-t border-slate-100', 'bg-white rounded-b-2xl border-t border-slate-100')

with open(file_path, 'w', encoding='utf-8') as f:
    f.write(content)


# 2. Add "Daftar " to tables and widen filterisasi in FrameAndFilterManajemen.php
file_path = 'app/Views/Admin/FrameAndFilter/FrameAndFilterManajemen.php'
with open(file_path, 'r', encoding='utf-8') as f:
    content = f.read()

# Add "Daftar"
content = content.replace('<h4 class="font-bold text-lg text-slate-800">Filter Warna</h4>', '<h4 class="font-bold text-lg text-slate-800">Daftar Filter Warna</h4>')
content = content.replace('<h4 class="font-bold text-lg text-slate-800">Kategori Frame</h4>', '<h4 class="font-bold text-lg text-slate-800">Daftar Kategori Frame</h4>')
content = content.replace('<h4 class="font-bold text-lg text-slate-800">Koleksi Frame</h4>', '<h4 class="font-bold text-lg text-slate-800">Daftar Koleksi Frame</h4>')

# Perlebar filterisasi (the select input for Filter Warna)
# Current: class="px-4 py-2 border border-slate-200 rounded-lg text-sm outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary bg-white transition-all text-slate-600 font-medium cursor-pointer"
# Let's add min-w-[240px] to make it wider, or w-full sm:w-64
content = content.replace(
    'class="px-4 py-2 border border-slate-200 rounded-lg text-sm outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary bg-white transition-all text-slate-600 font-medium cursor-pointer"',
    'class="px-4 py-2 w-full sm:w-64 border border-slate-200 rounded-lg text-sm outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary bg-white transition-all text-slate-600 font-medium cursor-pointer"'
)

with open(file_path, 'w', encoding='utf-8') as f:
    f.write(content)

print("SUCCESS")
