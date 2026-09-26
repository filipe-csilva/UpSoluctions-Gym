FROM php:8.3-cli
WORKDIR /var/www/html
RUN apt-get update && apt-get install -y git unzip libzip-dev libpng-dev && docker-php-ext-install pdo_mysql zip
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer
COPY . .
RUN composer install --no-dev --optimize-autoloader
EXPOSE 8000
CMD ["sh", "-c", "php artisan migrate --force && php artisan serve --host=0.0.0.0 --port=8000"]
