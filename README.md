# BoojBooks

## https://boojbooks.com

## Setup For Development
- `cd /path/to/code/folder`
- `git clone git@github.com:jayywolff/boojbooks.git .`
- `cd boojbooks`
- `composer install`
- `npm install`
- `cp .env.example .env`
- Create a mysql database
- Config db credentials `vim .env`
- `php artisan migrate`
- `php artisan db:seed`
- `php artisan key:generate`
- `npm run dev`
- Note it should be the same setup as any laravel project
- Assuming you are running vagrant, on linux, or using xamp or mamp
- import `./BoojBooks.postman_collection.json` into Postman for API testing

## Dependencies
- php 7.4
- composer
- mysql
- nodejs >= 10.x
- npm >= 6.x

## Testing
- run `./vendor/bin/phpunit` to run the full test suite. 
- Note: Test suite is a work in progress...

## Tech Stack <3
- Developed/deployed with Ubuntu 18.04, Nginx, MySQL, PHP 7.4 (LEMP stack)
- Laravel 7
- MySQL
- PHPUnit
- Vue.JS 2.6
- Bootstrap 4

## TODO list
- [ ] Connect to a publically available API
- [x] Create Postman collection and Vue app OR Laravel App
- [x] Add or remove items from the list
- [x] Change the order of the items in the list
- [x] Sort the list of items
- [x] Display a detail page with at least 3 points of data to display
- [ ] Include unit tests
- [x] Deploy it on the cloud - be prepared to describe your process on deployment
