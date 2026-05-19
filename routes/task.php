<?php

$view = $_GET['view'] ?? '';

if ($view === 'add') {
    require __DIR__ . '/../views/task/add-task.php';
    exit;
}

require __DIR__ . '/../controllers/TaskController.php';
