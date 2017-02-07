#!/usr/bin/env bash

# Upload PHPUnit code coverage
wget https://scrutinizer-ci.com/ocular.phar
php ocular.phar code-coverage:upload --format=php-clover build/clover.xml
