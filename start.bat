@echo off
start php -S localhost:8000 -t public
start "" "chrome" --kiosk --fullscreen "http://localhost:8000"