@echo off
start php -S localhost:8000 -t public
start php artisan queue:work --tries=5
start php artisan queue:listen
start "" "chrome" --kiosk --fullscreen "http://localhost:8000"