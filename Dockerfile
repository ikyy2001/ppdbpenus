FROM composer:latest

COPY . .

RUN apk add --no-cache postgresql-dev && \
    docker-php-ext-install pdo pdo_pgsql pgsql
    
RUN apk add nodejs && \
    apk add npm

RUN composer install && \
    npm install && \
    npm run build

RUN php artisan key:generate
RUN php artisan make:session-table

CMD [ "php", "artisan", "serve", "--host=0.0.0.0", "--port=80" ]

