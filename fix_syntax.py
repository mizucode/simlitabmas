import re

file_path = 'module/content/insert/penelitian.php'
with open(file_path, 'r', encoding='utf-8') as f:
    content = f.read()

# The error looks like:
# id_porto, nik, role, jenis_porto, date_regis, time_regis, registered_by
# ) VALUES (
# '$id_porto', '$nip_dosen', '1', 'patenhki', '$tgl_sekarang', '$jam_sekarang', '$jenis_file', '$registered_by'

# I will just replace `'$jenis_file', '$registered_by'` with `'$registered_by'`
# Wait, it could be `'$jenis_file'` with double quotes or whatever.
# Let's replace exactly: `, '\$jenis_file', '\$registered_by'` with `, '\$registered_by'`

content = content.replace(", '$jenis_file', '$registered_by'", ", '$registered_by'")
# Also for Penelitian, it uses $date_regis and $time_regis instead of $tgl_sekarang and $jam_sekarang
content = content.replace(", '$date_regis', '$time_regis', '$jenis_file', '$registered_by'", ", '$date_regis', '$time_regis', '$registered_by'")

with open(file_path, 'w', encoding='utf-8') as f:
    f.write(content)

print("Syntax errors fixed.")
