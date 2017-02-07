#!/usr/bin/env bash

SYMFONY_VERSION=${SYMFONY_VERSION-2.3.*}
COMPOSER_PREFER_LOWEST=${COMPOSER_PREFER_LOWEST-false}

# Update Composer
composer self-update

# Always remove PHP-CS-Fixer (PHP >= 5.6)
composer remove --no-update --dev friendsofphp/php-cs-fixer

# Fix Symfony versions
composer require --no-update symfony/framework-bundle:${SYMFONY_VERSION}
composer require --no-update symfony/form:${SYMFONY_VERSION}
composer require --no-update --dev symfony/templating:${SYMFONY_VERSION}
composer require --no-update --dev symfony/twig-bridge:${SYMFONY_VERSION}
composer require --no-update --dev symfony/yaml:${SYMFONY_VERSION}

if [[ "$SYMFONY_VERSION" =~ ^2\.[2-6] ]]; then
    composer require --no-update --dev symfony/asset:2.7.*
else
    composer require --no-update --dev symfony/asset:${SYMFONY_VERSION}
fi

# Use Composer "dev" minimum stability
if [[ "$SYMFONY_VERSION" = *dev* ]]; then
    sed -i "s/\"MIT\"/\"MIT\",\"minimum-stability\":\"dev\"/g" composer.json
fi

# Install dependencies
composer update --prefer-source `if [[ ${COMPOSER_PREFER_LOWEST} = true ]]; then echo "--prefer-lowest --prefer-stable"; fi`
