FROM php:8.1-apache

# Install ekstensi mysqli dan modul pendukung
RUN docker-php-ext-install mysqli && docker-php-ext-enable mysqli

# Aktifkan mod_rewrite (opsional jika pakai .htaccess)
RUN a2enmod rewrite

# Salin semua file project ke dalam container
COPY . /var/www/html/
