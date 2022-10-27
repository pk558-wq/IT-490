#!/bin/sh

# Install composer
# Notes here https://getcomposer.org/download/
# Or

# cd ~
wget -O /tmp/composer-setup.php https://getcomposer.org/installer

#* Check if composer is installed
#sudo mv composer.phar /usr/local/bin/composer
php /tmp/composer-setup.php
apt-get install php 7.4-mbstring

./composer.phar --version
./composer.phar install
