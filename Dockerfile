# Use the official PHP image with Apache
FROM php:8.2-apache

# Install required PHP extensions
RUN docker-php-ext-install mysqli pdo pdo_mysql

# Enable Apache mod_rewrite for clean URLs
RUN a2enmod rewrite

# Copy the application files to the Apache document root
COPY . /var/www/html/

# Set the working directory
WORKDIR /var/www/html/

# Set permissions for the web directory
RUN chown -R www-data:www-data /var/www/html

# Expose port 80 for the web server
EXPOSE 89