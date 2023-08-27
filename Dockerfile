# Utilisez l'image de base PHP avec la version souhaitée
FROM php:8.2-fpm

# Installez les dépendances système nécessaires
RUN apt-get update && apt-get install -y \
    libzip-dev \
    unzip \
    git \
    curl \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install gd pdo pdo_mysql mbstring zip

# Installez Composer globalement
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Installez Node.js 19.x et npm
RUN curl -fsSL https://deb.nodesource.com/setup_19.x | bash -
RUN apt-get install -y nodejs

# Définissez le répertoire de travail dans le conteneur
WORKDIR /var/www

# Copiez les fichiers du projet Laravel dans le conteneur
COPY . /var/www

# Installez les dépendances PHP avec Composer
RUN composer install

# Exposez le port 9000 pour PHP-FPM
EXPOSE 9000

# Commande par défaut pour exécuter PHP-FPM
CMD ["php-fpm"]

# Finalement, ajoutez ici les commandes pour configurer MySQL
# et exécutez les commandes nécessaires pour démarrer votre application Laravel avec MySQL
