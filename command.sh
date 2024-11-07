composer require laravel/sanctum
php artisan vendor:publish --provider="Laravel\Sanctum\SanctumServiceProvider"
php artisan install:api

sudo -i -u postgres
CREATE USER your_username WITH PASSWORD 'your_password';
CREATE DATABASE your_database_name;
GRANT ALL PRIVILEGES ON DATABASE your_database_name TO your_username;
\c your_database_name  
GRANT ALL PRIVILEGES ON SCHEMA public TO your_username;
ALTER USER your_username CREATEDB;
\q

CREATE EXTENSION IF NOT EXISTS "uuid-ossp";

php -m | grep gd
sudo apt-get update
sudo apt-get install php-gd
# sudo apt-get install php-gd --fix-missing
sudo service apache2 restart
sudo service nginx restart

php artisan session:table

php artisan vendor:publish --provider="Tymon\JWTAuth\Providers\LaravelServiceProvider"

php artisan jwt:dc4cb1b7beea826b298d37b1b60eeaa8f0fc7352c601fdce80b22eee2c84b8598fc8949828346966e5f1da3f4d6908c4a03457f9af3b0baf48b83080b2ee629f

php artisan cache:table