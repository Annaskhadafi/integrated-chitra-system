# Use the official PHP image with Apache
FROM php:8.2-apache

# Install system dependencies
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libzip-dev \
    libicu-dev \
    libonig-dev \
    libxml2-dev \
    unzip \
    && rm -rf /var/lib/apt/lists/*

# Install PHP extensions
RUN docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) \
    mysqli \
    pdo_mysql \
    gd \
    zip \
    intl \
    mbstring \
    xml

# Enable Apache mod_rewrite and mod_headers (for clickjacking protection)
RUN a2enmod rewrite headers

RUN echo "ServerTokens Prod" >> /etc/apache2/apache2.conf \
    && echo "ServerSignature Off" >> /etc/apache2/apache2.conf


# ==========================================
# ADD SECURITY CONFIGURATION HERE
# ==========================================
# Copy the security headers file and enable it
COPY security-headers.conf /etc/apache2/conf-available/security-headers.conf
RUN a2enconf security-headers
# ==========================================

# Sembunyikan versi PHP dari HTTP Response Header
RUN echo "expose_php = Off" > /usr/local/etc/php/conf.d/disable-expose-php.ini

# Configure Apache to allow .htaccess and set DocumentRoot
# We keep DocumentRoot at /var/www/html, which corresponds to the project root.
RUN sed -i 's|/var/www/html|/var/www/html|g' /etc/apache2/sites-available/000-default.conf
RUN echo '<Directory "/var/www/html">\n\
    AllowOverride All\n\
    Require all granted\n\
</Directory>' >> /etc/apache2/apache2.conf

# Set the working directory
WORKDIR /var/www/html

# Copy the project files to the container
COPY . /var/www/html/

# Set permissions for the web server
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html

# Expose port 80
EXPOSE 80

# Start Apache in the foreground
CMD ["apache2-foreground"]
