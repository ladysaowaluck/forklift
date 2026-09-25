@echo off
cd C:\xampp\htdocs\forklift-tracker
C:\xampp\php\php.exe artisan schedule:run >> scheduler.log 2>&1