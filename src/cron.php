<?php
require_once 'functions.php';


if (php_sapi_name() === 'cli' && empty($_SERVER['HTTP_HOST'])) {
    $_SERVER['HTTP_HOST'] = 'localhost'; 
}

sendTaskReminders();

echo "Reminder emails sent.\n";
