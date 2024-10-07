@echo off
REM Create main directories
mkdir project_root
cd project_root
mkdir assets includes pages api vendor

REM Create subdirectories in assets
cd assets
mkdir css js images
cd ..

REM Create subdirectory in vendor
cd vendor
mkdir bootstrap
cd ..

REM Create files in includes
cd includes
type nul > config.php
type nul > db.php
type nul > functions.php
cd ..

REM Create files in pages
cd pages
type nul > timbang.php
type nul > histori.php
type nul > setting.php
type nul > harga.php
cd ..

REM Create files in api
cd api
type nul > timbang.php
type nul > histori.php
type nul > setting.php
type nul > harga.php
cd ..

REM Create main files
type nul > index.php
type nul > .htaccess

REM Create empty CSS and JS files
cd assets\css
type nul > styles.css
cd ..\js
type nul > main.js
cd ..\..

echo Folder structure created successfully!
pause