@echo off
setlocal

REM === Edit these two paths below as per your machine ===
set PHP_PATH=C:\xampp\php\php.exe
set CRON_SCRIPT=C:\xampp\htdocs\Souvik\src\cron.php

REM === Create the scheduled task ===
schtasks /create ^
 /tn "TaskSchedulerReminder" ^
 /tr "\"%PHP_PATH%\" \"%CRON_SCRIPT%\"" ^
 /sc hourly ^
 /f

echo.
echo ✅ Windows Task Scheduler job 'TaskSchedulerReminder' created to run every 1 hour.
pause
