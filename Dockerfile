FROM php:8.1-apache

# Salin semua file ke direktori web server Apache
COPY . /var/www/html/

# Aktifkan mod_rewrite jika pakai .htaccess
RUN a2enmod rewrite
