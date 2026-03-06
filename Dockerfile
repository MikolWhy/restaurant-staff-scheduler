FROM php:8.3-fpm

# Install system dependencies 

RUN apt-get update && apt-get install -y \
    git \
    curl \
    zip \
    libonig-dev \ 
    unzip
    #Not needed:libpng-dev \ #libxml2-dev \

# Clear cache
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Install PHP extensions required by Laravel
RUN docker-php-ext-install pdo_mysql mbstring
# Not needed:mbstring, pcntl, bcmath, gd

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www

#Copy the code into the container, good practice for production.
#Have to create a .dockerignore file to reduce bloating the image with node modules etc
#COPY . /var/www

# Set permissions for Laravel
RUN chown -R www-data:www-data /var/www

EXPOSE 9000
CMD ["php-fpm"]

