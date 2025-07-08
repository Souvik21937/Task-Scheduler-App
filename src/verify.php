<?php
require_once 'functions.php';

$email = $_GET['email'] ?? '';
$code = $_GET['code'] ?? '';

if ($email && $code) {
    $success = verifySubscription($email, $code);
    echo $success
        ? "<h2>Email verified successfully! ✅</h2>"
        : "<h2>Invalid or expired verification link ❌</h2>";
} else {
    echo "<h2>Missing verification data ❌</h2>";
}
