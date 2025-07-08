<?php

// Loads tasks from tasks.txt
function getAllTasks() {
    $file = 'tasks.txt';
    if (!file_exists($file)) {
        file_put_contents($file, '[]');
    }
    $tasks = json_decode(file_get_contents($file), true);
    return is_array($tasks) ? $tasks : [];
}

// Saves tasks to tasks.txt
function saveTasks($tasks) {
    file_put_contents('tasks.txt', json_encode($tasks, JSON_PRETTY_PRINT));
}

// Add a new task
function addTask($task_name) {
    $task_name = trim($task_name);
    if ($task_name === '') return;

    $tasks = getAllTasks();

    // Avoid duplicates
    foreach ($tasks as $task) {
        if (strtolower($task['name']) === strtolower($task_name)) {
            return;
        }
    }

    $tasks[] = [
        'id' => uniqid(),
        'name' => $task_name,
        'completed' => false
    ];
    saveTasks($tasks);
}

// Mark or unmark a task as completed
function markTaskAsCompleted($task_id, $is_completed) {
    $tasks = getAllTasks();
    foreach ($tasks as &$task) {
        if ($task['id'] === $task_id) {
            $task['completed'] = (bool) $is_completed;
            break;
        }
    }
    saveTasks($tasks);
}

// Delete a task by ID
function deleteTask($task_id) {
    $tasks = getAllTasks();
    $tasks = array_filter($tasks, fn($task) => $task['id'] !== $task_id);
    saveTasks(array_values($tasks));
}

// Generate 6-digit verification code
function generateVerificationCode() {
    return str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
}

// Subscribe user by storing in pending_subscriptions.txt and sending email
function subscribeEmail($email) {
    $email = strtolower(trim($email));
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) return;

    $file = 'pending_subscriptions.txt';
    $pending = file_exists($file) ? json_decode(file_get_contents($file), true) : [];

    // Avoid resending if already pending
    if (isset($pending[$email])) return;

    $code = generateVerificationCode();
    $pending[$email] = [
        'code' => $code,
        'timestamp' => time()
    ];
    file_put_contents($file, json_encode($pending, JSON_PRETTY_PRINT));

    // Send verification email
    $verification_link = "http://{$_SERVER['HTTP_HOST']}/src/verify.php?email=" . urlencode($email) . "&code=$code";
    $subject = "Verify subscription to Task Planner";
    $headers = "From: no-reply@example.com\r\nContent-type: text/html\r\n";
    $message = "
        <p>Click the link below to verify your subscription to Task Planner:</p>
        <p><a id='verification-link' href='$verification_link'>Verify Subscription</a></p>
    ";

    mail($email, $subject, $message, $headers);
}

// Verify a user by matching code
function verifySubscription($email, $code) {
    $email = strtolower(trim($email));
    $file = 'pending_subscriptions.txt';
    $sub_file = 'subscribers.txt';

    $pending = file_exists($file) ? json_decode(file_get_contents($file), true) : [];
    if (!isset($pending[$email]) || $pending[$email]['code'] !== $code) return false;

    unset($pending[$email]);
    file_put_contents($file, json_encode($pending, JSON_PRETTY_PRINT));

    $subscribers = file_exists($sub_file) ? json_decode(file_get_contents($sub_file), true) : [];
    if (!in_array($email, $subscribers)) {
        $subscribers[] = $email;
    }
    file_put_contents($sub_file, json_encode($subscribers, JSON_PRETTY_PRINT));
    return true;
}

// Unsubscribe a user
function unsubscribeEmail($email) {
    $email = strtolower(trim($email));
    $file = 'subscribers.txt';
    $subscribers = file_exists($file) ? json_decode(file_get_contents($file), true) : [];
    $subscribers = array_values(array_filter($subscribers, fn($e) => $e !== $email));
    file_put_contents($file, json_encode($subscribers, JSON_PRETTY_PRINT));
}

// Send reminders to all verified users
function sendTaskReminders() {
    $subscribers = file_exists('subscribers.txt') ? json_decode(file_get_contents('subscribers.txt'), true) : [];
    $tasks = getAllTasks();
    $pending_tasks = array_filter($tasks, fn($task) => !$task['completed']);

    foreach ($subscribers as $email) {
        sendTaskEmail($email, $pending_tasks);
    }
}

// Send a single email with pending tasks and unsubscribe link
function sendTaskEmail($email, $pending_tasks) {
    $unsubscribe_link = "http://{$_SERVER['HTTP_HOST']}/src/unsubscribe.php?email=" . urlencode($email);
    $subject = "Task Planner - Pending Tasks Reminder";
    $headers = "From: no-reply@example.com\r\nContent-type: text/html\r\n";

    $task_list = '';
    foreach ($pending_tasks as $task) {
        $task_list .= "<li>" . htmlspecialchars($task['name']) . "</li>";
    }

    $message = "
        <h2>Pending Tasks Reminder</h2>
        <p>Here are the current pending tasks:</p>
        <ul>$task_list</ul>
        <p><a id='unsubscribe-link' href='$unsubscribe_link'>Unsubscribe from notifications</a></p>
    ";

    mail($email, $subject, $message, $headers);
}
