@echo off
cd C:\xampp\htdocs\forklift_tracker
C:\xampp\php\php.exe artisan schedule:run >> scheduler.log 2>&1