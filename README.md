Langkah migrate database

1. php artisan config:clear 
2. php artisan cache:clear
3. php artisan migrate:fresh
4. php artisan db:seed
5. php artisan shield:generate --all
6. php artisan storage:link