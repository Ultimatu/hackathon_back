FROM ubuntu:latest
LABEL authors="tonde"
# laravel setup

# use php8.2, mysql8, nginx, composer, nodejs
RUN apt-get update && apt-get install -y \
    php8.0 \
    php8.0-mysql \
    php8.0-mbstring \
    php8.0-xml \
    php8.0-zip \
    php8.0-gd \
    php8.0-curl \
    php8.0-bcmath \
    php8.0-intl \
    php8.0-fpm \
    mysql-server \
    nginx \
    composer \
    nodejs \
    npm \
    vim \
    git \
    zip \
    unzip \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*


ENTRYPOINT ["top", "-b"]
