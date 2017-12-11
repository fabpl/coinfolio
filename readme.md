# Coinfolio

## About

Altcoin Portfolio

Coinfolio offers cryptocurrency management. Get detailed price and market information for individual currencies all in one place.

## Install

    composer install
    php artisan migrate --seed
    php artisan price:update
    php artisan exchenge:update bittrex
    php artisan exchenge:update kraken
    php artisan serve
    
Open http://127.0.0.1:8000 an login with :
    
    email : test@test.local
    password : test

Enjoy!