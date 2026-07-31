import os

dir_path = 'module/content/penelitian/lppm/anggota'
files = ['buku.php', 'jurnal.php', 'karya.php', 'patenhki.php', 'prosiding.php', 'penelitian.php']

for file_name in files:
    path = os.path.join(dir_path, file_name)
    if not os.path.exists(path): continue
    
    with open(path, 'r', encoding='utf-8') as f:
        content = f.read()
    
    content = content.replace('SELECT id_cat, nip_dosen, kelompok, MAX(url) as url', 
                              'SELECT id_cat, nip_dosen, kelompok, MAX(url) as url, MAX(value_kode) as value_kode')
    content = content.replace('ON r.type_file = k2.value', 'ON f.value_kode = k2.value')
    content = content.replace("$row['type_file']", "$row['value_kode']")
    
    with open(path, 'w', encoding='utf-8') as f:
        f.write(content)

print("Done")
