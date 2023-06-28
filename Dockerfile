FROM php:8.1-fpm

ARG user
ARG uid

# Copy composer.lock and composer.json
# COPY composer.lock composer.json /var/www/


# Install dependencies
RUN apt-get update && apt-get install -y \
    libicu-dev \
    software-properties-common \
    libssl-dev \
    pkg-config

RUN docker-php-ext-configure intl \
&& docker-php-ext-install intl


RUN docker-php-ext-install \
    bcmath \
    opcache \
    calendar \
    pdo_mysql \
    mysqli


RUN apt-get install -y p7zip \
    p7zip-full \
    unace \
    zip

# Clear cache
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Install extensions
# RUN docker-php-ext-install pdo_mysql mbstring zip exif pcntl
# RUN docker-php-ext-configure gd --with-gd --with-freetype-dir=/usr/include/ --with-jpeg-dir=/usr/include/ --with-png-dir=/usr/include/
# RUN docker-php-ext-install gd


# Install composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Add user for laravel application
#RUN groupadd -g 1000 'boris'
#RUN useradd -u 1000 -ms /bin/bash -g 'boris' 'boris'

# Copy existing application directory contents
#COPY . /var/www

# Copy existing application directory permissions
#COPY --chown='boris':'boris' . /var/www

# Change current user to www
#USER 'boris'

RUN useradd -G www-data,root -u $uid -d /home/$user $user
RUN mkdir -p /home/$user/.composer && \
    chown -R $user:$user /home/$user

WORKDIR /var/www/practice2023

USER $user

# Expose port 9000 and start php-fpm server
EXPOSE 9000
CMD ["php-fpm"]
