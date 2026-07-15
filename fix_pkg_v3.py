import os

file_path = 'app/Views/Admin/PackageAndPrintOption/PackageAndPrintOptionManajemen.php'
with open(file_path, 'r', encoding='utf-8') as f:
    content = f.read()

# Ubah ikon Edit ke text-blue-600 tanpa hover background default, background pas hover
content = content.replace('text-slate-400 hover:text-blue-600 hover:bg-blue-50', 'text-blue-600 hover:bg-blue-50 hover:text-blue-700')

# Ubah ikon Hapus ke text-red-600 tanpa hover background default, background pas hover
content = content.replace('text-slate-400 hover:text-red-600 hover:bg-red-50', 'text-red-600 hover:bg-red-50 hover:text-red-700')

with open(file_path, 'w', encoding='utf-8') as f:
    f.write(content)
print("SUCCESS")
