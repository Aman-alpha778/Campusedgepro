FROM php:8.3-cli

WORKDIR /app

RUN apt-get update && apt-get install -y \
    git \
    unzip \
    curl \
    libpq-dev \
    nodejs \
    npm

RUN docker-php-ext-install pdo pdo_pgsql

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

COPY . .

RUN composer install --no-dev --optimize-autoloader

RUN npm install

RUN npm run build

RUN chmod -R 777 storage bootstrap/cache

EXPOSE 10000

CMD php -S 0.0.0.0:${PORT:-10000} -t public