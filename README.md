# Trauma Analytics Website

## Requirements
- PHP >= 5.5.9
- OpenSSL PHP Extension
- Mbstring PHP Extension
- Tokenizer PHP Extension
- MySQL
- Composer
- Node JS

## Installation Steps

### Install dependencies
- `composer install`
- `composer dump-autoload --optimize`
- `npm install --save-dev`
- `bower install`

### Set Application Key
- `php artisan key:generate`

### Create database
Create a database on your MySQL server. You must create database with utf-8 collation(uft8_general_ci), to install and application work perfectly. After that, copy .env.example and rename it as .env and put connection and change default database connection name, only database connection, put name database, database username and password.

Then run these commands to setup the database
- `php artisan migrate`
- `php artisan db:seed`

### Compile assets
Gulp is used in this project to precompile SASS and merge JavaScript files. You can simply run
- `gulp`


### Deploying the updates
- `php artisan migrate` # to update the database
- `gulp` # to build front-end asset files (CSS, JS)
- Run `bower install` and `composer install` as needed.

## Server Setup
Please read `SERVER.md`.
