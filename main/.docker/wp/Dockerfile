FROM wordpress:5.8.1-php8.0-apache

RUN curl --location --output /usr/local/bin/mhsendmail https://github.com/mailhog/mhsendmail/releases/download/v0.2.0/mhsendmail_linux_amd64 && \
    chmod +x /usr/local/bin/mhsendmail

RUN echo 'sendmail_path="/usr/local/bin/mhsendmail --smtp-addr=ea-mailhog:1025 --from=no-reply@docker.dev"' > /usr/local/etc/php/conf.d/mailhog.ini

# Install dependencies
RUN apt-get update

# XDEBUG
RUN pecl install xdebug
RUN docker-php-ext-enable xdebug
# This needs in order to run xdebug from PhpStorm
ENV PHP_IDE_CONFIG 'serverName=DockerApp'

COPY ./xdebug.ini /usr/local/etc/php/conf.d/xdebug.ini