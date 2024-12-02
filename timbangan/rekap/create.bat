@echo off
echo Creating directory structure for Timbangan Rekap...

:: Create main directory
mkdir timbangan_rekap
cd timbangan_rekap

:: Create subdirectories
mkdir api
mkdir api\inventory
mkdir api\master
mkdir api\hitung

mkdir assets
mkdir assets\css
mkdir assets\js

mkdir includes

mkdir pages

:: Create empty files in api/inventory
echo. > api\inventory\stock.php
echo. > api\inventory\barang_masuk.php
echo. > api\inventory\barang_keluar.php
echo. > api\inventory\biaya_tenaga.php

:: Create empty files in api/master
echo. > api\master\kategori.php
echo. > api\master\produk.php
echo. > api\master\supplier.php
echo. > api\master\customer.php

:: Create empty files in api/hitung
echo. > api\hitung\supplier.php
echo. > api\hitung\tenaga.php

:: Create empty files in assets
echo. > assets\css\style.css
echo. > assets\js\inventory.js
echo. > assets\js\master.js
echo. > assets\js\hitung.js

:: Create empty files in includes
echo. > includes\config.php
echo. > includes\db.php
echo. > includes\functions.php
echo. > includes\navbar.php

:: Create empty files in pages
echo. > pages\dashboard.php
echo. > pages\barang_masuk.php
echo. > pages\barang_keluar.php
echo. > pages\timbang_biaya.php
echo. > pages\master.php
echo. > pages\hitung.php

:: Create index.php in root
echo. > index.php

echo Directory structure created successfully!
cd ..

echo Creating SQL script file...
echo -- SQL script for creating database tables > create_tables.sql

:: Add SQL content to the file
(
echo -- Tabel kategori
echo CREATE TABLE tr_kategori (
echo     id INT PRIMARY KEY AUTO_INCREMENT,
echo     nama VARCHAR(100) NOT NULL,
echo     keterangan TEXT,
echo     created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
echo ^);
echo.
echo -- Tabel supplier
echo CREATE TABLE tr_supplier (
echo     id INT PRIMARY KEY AUTO_INCREMENT,
echo     nama VARCHAR(100) NOT NULL,
echo     alamat TEXT,
echo     telepon VARCHAR(20),
echo     kontak_person VARCHAR(100),
echo     created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
echo ^);
echo.
echo -- Tabel customer
echo CREATE TABLE tr_customer (
echo     id INT PRIMARY KEY AUTO_INCREMENT,
echo     nama VARCHAR(100) NOT NULL,
echo     alamat TEXT,
echo     telepon VARCHAR(20),
echo     kontak_person VARCHAR(100),
echo     created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
echo ^);
echo.
echo -- Tabel karyawan
echo CREATE TABLE tr_karyawan (
echo     id INT PRIMARY KEY AUTO_INCREMENT,
echo     nama VARCHAR(100) NOT NULL,
echo     alamat TEXT,
echo     telepon VARCHAR(20),
echo     created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
echo ^);
echo.
echo -- Tabel produk
echo CREATE TABLE tr_produk (
echo     id INT PRIMARY KEY AUTO_INCREMENT,
echo     kategori_id INT,
echo     nama VARCHAR(100) NOT NULL,
echo     created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
echo     FOREIGN KEY (kategori_id^) REFERENCES tr_kategori(id^)
echo ^);
echo.
echo -- Tabel barang masuk
echo CREATE TABLE tr_barang_masuk (
echo     id INT PRIMARY KEY AUTO_INCREMENT,
echo     supplier_id INT,
echo     produk_id INT,
echo     berat DECIMAL(10,2) NOT NULL,
echo     keterangan TEXT,
echo     tanggal TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
echo     FOREIGN KEY (supplier_id^) REFERENCES tr_supplier(id^),
echo     FOREIGN KEY (produk_id^) REFERENCES tr_produk(id^)
echo ^);
echo.
echo -- Tabel barang keluar
echo CREATE TABLE tr_barang_keluar (
echo     id INT PRIMARY KEY AUTO_INCREMENT,
echo     customer_id INT,
echo     produk_id INT,
echo     berat DECIMAL(10,2) NOT NULL,
echo     keterangan TEXT,
echo     tanggal TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
echo     FOREIGN KEY (customer_id^) REFERENCES tr_customer(id^),
echo     FOREIGN KEY (produk_id^) REFERENCES tr_produk(id^)
echo ^);
echo.
echo -- Tabel biaya tenaga kerja
echo CREATE TABLE tr_biaya_tenaga (
echo     id INT PRIMARY KEY AUTO_INCREMENT,
echo     karyawan_id INT,
echo     produk_id INT,
echo     berat DECIMAL(10,2) NOT NULL,
echo     biaya_per_kg DECIMAL(10,2) NOT NULL,
echo     keterangan TEXT,
echo     tanggal TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
echo     FOREIGN KEY (karyawan_id^) REFERENCES tr_karyawan(id^),
echo     FOREIGN KEY (produk_id^) REFERENCES tr_produk(id^)
echo ^);
echo.
echo -- Tabel perakitan produk
echo CREATE TABLE tr_perakitan (
echo     id INT PRIMARY KEY AUTO_INCREMENT,
echo     produk_id INT,
echo     komponen_id INT,
echo     jumlah DECIMAL(10,2) NOT NULL,
echo     created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
echo     FOREIGN KEY (produk_id^) REFERENCES tr_produk(id^),
echo     FOREIGN KEY (komponen_id^) REFERENCES tr_produk(id^)
echo ^);
echo.
echo -- Indeks untuk optimasi query
echo CREATE INDEX idx_tr_barang_masuk_tanggal ON tr_barang_masuk(tanggal^);
echo CREATE INDEX idx_tr_barang_keluar_tanggal ON tr_barang_keluar(tanggal^);
echo CREATE INDEX idx_tr_biaya_tenaga_tanggal ON tr_biaya_tenaga(tanggal^);
echo CREATE INDEX idx_tr_produk_kategori ON tr_produk(kategori_id^);
) >> create_tables.sql

echo All files and directories have been created successfully!
pause