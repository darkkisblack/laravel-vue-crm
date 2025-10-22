FROM php:8.4-fpm

# Установка системных зависимостей
RUN apt-get update && apt-get install -y \
    zip unzip git curl libpng-dev libjpeg-dev libfreetype6-dev \
    libonig-dev libxml2-dev libpq-dev nodejs npm \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install pdo pdo_pgsql mbstring exif pcntl bcmath gd

# Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Node.js для сборки фронтенда
RUN curl -fsSL https://deb.nodesource.com/setup_18.x | bash - \
    && apt-get install -y nodejs

WORKDIR /var/www

# Копируем все файлы для установки зависимостей
COPY . .

# Установка зависимостей
RUN composer install --no-dev --optimize-autoloader
RUN npm install --legacy-peer-deps

CMD ["php-fpm"]
