FROM php:8.1-apache

# Install paket sistem yang dibutuhkan (khususnya untuk PostgreSQL)
RUN apt-get update && apt-get install -y \
    libpq-dev \
    libicu-dev \
    libzip-dev \
    zip \
    unzip \
    git \
    && docker-php-ext-install intl pdo pdo_pgsql pgsql zip

# Mengaktifkan URL Rewrite Apache (Wajib untuk CodeIgniter 4)
RUN a2enmod rewrite

# Mengubah Document Root Apache agar mengarah ke folder public/
ENV APACHE_DOCUMENT_ROOT /var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

# Mengatur direktori kerja
WORKDIR /var/www/html

# Menyalin seluruh kode aplikasi ke dalam container
COPY . .

# Memberikan hak akses penulisan ke folder writable (Wajib untuk CI4)
RUN chown -R www-data:www-data /var/www/html/writable /var/www/html/public/img

# Expose port 80
EXPOSE 80
