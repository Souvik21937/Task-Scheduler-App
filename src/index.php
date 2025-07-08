<?php
require_once 'functions.php';

// Handle Add Task
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['task-name'])) {
        addTask($_POST['task-name']);
    } elseif (isset($_POST['email'])) {
        subscribeEmail($_POST['email']);
    } elseif (isset($_POST['mark'])) {
        markTaskAsCompleted($_POST['mark'], isset($_POST['completed']) ? true : false);
    } elseif (isset($_POST['delete'])) {
        deleteTask($_POST['delete']);
    }
}

// Load tasks
$tasks = getAllTasks();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Task Scheduler</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .completed {
            text-decoration: line-through;
            opacity: 0.6;
        }
    </style>
</head>
<body class="bg-light">
    <div class="container py-5">
        <h1 class="mb-4 text-center">🗓️ Task Scheduler</h1>

        <!-- Task Form -->
        <div class="card mb-4">
            <div class="card-header">Add New Task</div>
            <div class="card-body">
                <form method="POST" class="d-flex gap-2">
                    <input type="text" name="task-name" id="task-name" class="form-control" placeholder="Enter new task" required>
                    <button type="submit" id="add-task" class="btn btn-primary">Add Task</button>
                </form>
            </div>
        </div>

        <!-- Task List -->
        <div class="card mb-4">
            <div class="card-header">Task List</div>
            <div class="card-body">
                <ul class="list-group tasks-list">
                    <?php foreach ($tasks as $task): ?>
                        <li class="list-group-item d-flex justify-content-between align-items-center task-item <?= $task['completed'] ? 'completed' : '' ?>">
                            <form method="POST" class="d-flex align-items-center gap-2 mb-0">
                                <input type="hidden" name="mark" value="<?= $task['id'] ?>">
                                <input type="checkbox" class="form-check-input task-status" name="completed" onchange="this.form.submit()" <?= $task['completed'] ? 'checked' : '' ?>>
                                <span><?= htmlspecialchars($task['name']) ?></span>
                            </form>
                            <form method="POST" class="mb-0">
                                <button type="submit" name="delete" value="<?= $task['id'] ?>" class="btn btn-danger btn-sm delete-task">Delete</button>
                            </form>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>

        <!-- Email Subscription Form -->
        <div class="card">
            <div class="card-header">Subscribe for Email Reminders</div>
            <div class="card-body">
                <form method="POST" class="d-flex gap-2">
                    <input type="email" name="email" class="form-control" placeholder="Enter your email" required>
                    <button type="submit" id="submit-email" class="btn btn-success">Subscribe</button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
