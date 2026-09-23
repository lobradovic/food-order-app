FROM php:8.4-cli

RUN apt-get update && apt-get install -y \
    git \
    unzip \
    libsqlite3-dev \
    nodejs \
    npm \
    && docker-php-ext-install pdo_sqlite

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /app

COPY . .

RUN composer install
RUN npm install
RUN npm run build

RUN php artisan migrate:fresh --seed

EXPOSE 8000
EXPOSE 5173

CMD ["php", "artisan", "serve", "--host", "0.0.0.0"]