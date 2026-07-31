import os

for path in ['module/content/insert/penelitian.php', 'module/content/update/penelitian.php']:
    with open(path, 'r', encoding='utf-8') as f:
        content = f.read()
    
    # For insert:
    content = content.replace('type_file, registered_by', 'registered_by')
    content = content.replace("'$type_file', '$registered_by'", "'$registered_by'")
    
    # For update:
    content = content.replace(", type_file = '$type_file'", "")
    
    with open(path, 'w', encoding='utf-8') as f:
        f.write(content)
