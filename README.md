Langkah migrate database

php artisan config:clear 
php artisan cache:clear
php artisan migrate:fresh
php artisan db:seed
php artisan shield:generate --all