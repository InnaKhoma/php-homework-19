# How to get started with the project?

+ Execute docker-compose.yaml


    docker-compose up -d
    
    
+ Copy .env.example and change it to .env with DB_HOST as mysql


    DB_HOST=mysql
    
    
+ Run the composer image in php-fpm container

  
    docker run --rm --interactive --tty \
      --volume $PWD:/app \
      composer install

+ Run all migrations and seeds in php-fpm container


    php artisan migrate --seed
    
+ Push jobs


    php artisan queue:work
    
+ Load http://localhost in your browser.
+ Register as a new user (use mailhog at http://localhost:8025/ to verify the email).
+ Or login using first user (id=1) from the database to test admin functions (filters, bulk actions).
