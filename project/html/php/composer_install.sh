#!/bin/sh

# Install composer
# Notes here https://getcomposer.org/download/
# Or

# cd ~
curl -sS https://getcomposer.org/installer -o /tmp/composer-setup.php

#* Check if composer is installed
#sudo mv composer.phar /usr/local/bin/composer
php /tmp/composer-setup.php
composer --version
composer install
