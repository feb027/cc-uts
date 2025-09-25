# Mulai dari image dasar PHP 8.1 dengan Apache
FROM php:8.1-apache

# Jalankan perintah di dalam container untuk menginstal ekstensi yang dibutuhkan
# pdo dan pdo_mysql adalah yang kita perlukan untuk koneksi database
RUN docker-php-ext-install pdo pdo_mysql
