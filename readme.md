# Coinfolio

## About

Altcoin Portfolio

Coinfolio offers cryptocurrency management. Get detailed price and market information for individual currencies all in one place.

## Required

    php >=7.0.0
    composer
    
And this extension

    php_curl with curl.cainfo="path/to/curl-ca-bundle.crt"
    php_pdo_sqlite
    
## Install

    composer install
    php artisan migrate --seed
    php artisan price:update
    php artisan exchange:update bittrex
    php artisan exchange:update kraken
    php artisan serve
    
Open [http://127.0.0.1:8000](http://127.0.0.1:8000) and login with :
    
    email : test@test.local
    password : test

Enjoy!