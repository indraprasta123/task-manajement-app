<?php

$view = $_GET['view'] ?? '';

if ($view === 'add') {
    require __DIR__ . '/../views/task/add-task.php';
    exit;
}

if ($view === 'edit') {
    $taskId = (int)($_GET['id'] ?? 0);
    if ($taskId > 0) {
        require __DIR__ . '/../views/task/edit-task.php';
        exit;
    }
}

require __DIR__ . '/../controllers/TaskController.php';
