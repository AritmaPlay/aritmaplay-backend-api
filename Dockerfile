FROM php:8.2-apache
#Update & install some requirement
RUN apt-get update && apt-get install -y \
    zlib1g-dev \
    libzip-dev \
    libpng-dev 
#Docker PHP ext
RUN docker-php-ext-install zip pdo_mysql gd
#Enable Module
RUN a2enmod rewrite
#Create Dir & Copy Apache config
RUN mkdir /var/www/html/app
COPY ./app.conf /etc/apache2/sites-available/
#Copy APP [see in .dockerignore]
COPY . /var/www/html/app
#Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
#Download Vendor to dir app
RUN cd /var/www/html/app && composer install
#Permission
RUN chmod -R 777 /var/www/html/app/storage
#Enable VHOST
RUN a2dissite 000-default.conf
RUN a2ensite app.conf

