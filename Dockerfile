FROM php:7-apache

RUN mv "$PHP_INI_DIR/php.ini-production" "$PHP_INI_DIR/php.ini"

COPY . /var/www/html/

RUN chown -R www-data:www-data /var/www/html && \
    chmod -R 755 /var/www/html/

# HEALTHCHECK CMD curl --fail http://localhost/health-check.php || exit 1
# HEALTHCHECK --interval=30s --start-period=5s --timeout=5s CMD curl --silent --fail http://localhost/health-check.php || exit 1