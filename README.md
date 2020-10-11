# How to get started with the project?

+ Execute docker-compose.yaml


    docker-compose up -d
    
    
+ Copy .env.example and change it to .env with DB_HOST as mysql and set your password at DB_PASSWORD


    DB_HOST=mysql
    DB_PASSWORD=your_password_here
    
    
+ Run the composer image in php-fpm container

  
    docker run --rm --interactive --tty \
      --volume $PWD:/app \
      composer install

+ Generate app key for laravel in php-fpm container


    php artisan key:generate

+ Run all migrations and seeds in php-fpm container


    php artisan migrate --seed
    
+ Push jobs


    php artisan queue:work
    
+ Load http://localhost in your browser.
+ Register as a new user. You can verify the email with mailhog at http://localhost:8025/ For this change next parameters in .env file:


    MAIL_HOST=mailhog
    MAIL_PORT=1025
    MAIL_FROM_ADDRESS=sender@gmail.com


+ Or login using first user (id=1) from the database to test admin functions (filters, bulk actions).
