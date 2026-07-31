import os

directory = 'module/content/penelitian/lppm/validasi/'
files = ['buku.php', 'jurnal.php', 'karya.php', 'patenhki.php', 'penelitian.php', 'prosiding.php']

for file in files:
    path = os.path.join(directory, file)
    if os.path.exists(path):
        with open(path, 'r', encoding='utf-8') as f:
            content = f.read()
        
        # Replace $registered_by = mysqli_real_escape_string($con, @$_SESSION['ses_user']);
        # with $registered_by = $nip_dosen;
        new_content = content.replace(
            "$registered_by = mysqli_real_escape_string($con, @$_SESSION['ses_user']);", 
            "$registered_by = $nip_dosen;"
        )
        
        with open(path, 'w', encoding='utf-8') as f:
            f.write(new_content)

print("Done")
