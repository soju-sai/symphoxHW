#!/bin/bash
sed -i -e '$aalias phpunit="/var/www/html/vendor/bin/phpunit"' ~/.bashrc
source ~/.bashrc