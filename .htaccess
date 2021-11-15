<FilesMatch ".*\.(log|ini|htaccess)$">
    deny from all
</FilesMatch>

Options -Indexes
DirectoryIndex index.php
RewriteEngine On
RewriteBase /
FallbackResource "index.php"
# Opcion 1



# Opcion 2
RewriteRule ^(public)($|/) - [L,NC]
RewriteRule ^(.*)/(.*)/(.*)$ index.php?module=$1&action=$2&$3 [L,QSA]
RewriteRule ^(.*)/(.*)$ index.php?module=$1&action=$2 [L,QSA]
RewriteRule ^(.*)$ index.php?module=$1&action=execute [L,QSA]




