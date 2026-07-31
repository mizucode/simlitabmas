import os
import re

directory = 'module/content/penelitian/lppm/anggota/'
files = ['buku.php', 'jurnal.php', 'karya.php', 'patenhki.php', 'penelitian.php', 'prosiding.php']

regex = re.compile(
    r'<\?php if \(\$row\[\'nik\'\] == \$porto\[\'nip_dosen\'\]\): \?>\s*<button.*?(?:<i class="fas fa-lock"></i>.*?</button>\s*)?<\?php else: \?>\s*(.*?)\s*<\?php endif; \?>', 
    re.DOTALL
)

for file in files:
    path = os.path.join(directory, file)
    if os.path.exists(path):
        with open(path, 'r', encoding='utf-8') as f:
            content = f.read()
            
        new_content = regex.sub(r'\1', content)
        
        with open(path, 'w', encoding='utf-8') as f:
            f.write(new_content)
        
print("Done")
