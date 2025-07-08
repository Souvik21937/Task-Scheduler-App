<?php
require_once 'functions.php';

$email = $_GET['email'] ?? '';

if ($email) {
    unsubscribeEmail($email);
    echo "<h2>You have been unsubscribed from reminders. ✅</h2>";
} else {
    echo "<h2>Invalid unsubscribe request ❌</h2>";
}
