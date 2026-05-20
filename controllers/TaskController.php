<?php

session_start();

//auth guard
if (!isset($_SESSION['user_id']) || !isset($_COOKIE['auth_token'])) {
    header('Location: /views/auth/login.php');
    exit;
}

require __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../models/TaskModel.php';

// logout
if (isset($_POST['logout'])) {
    $_SESSION = [];

    if (isset($_COOKIE['auth_token'])) {
        setcookie('auth_token', '', time() - 3600, '/');
    }

    session_destroy();
    header('Location: /views/auth/login.php');
    exit;
}

$taskModel = new TaskModel($conn);
$userId = (int)$_SESSION['user_id'];
$taskView = $_GET['view'] ?? 'dashboard';

//alert due task
$todayTasks = $taskModel->getTodayTasks($userId);
$todayDueCount = count(array_filter($todayTasks, function ($task) {
    $status = strtolower((string)($task['status'] ?? ''));
    return $status !== 'completed' && $status !== 'done';
}));

//add task

if (isset($_POST['action']) && $_POST['action'] === 'create_task') {
    $title = trim($_POST['title'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $priority = trim($_POST['priority'] ?? '');
    $dueDate = trim($_POST['due_date'] ?? '');

    if ($title !== '' && $priority !== '') {
        $taskModel->createTask(
            $userId,
            $title,
            $description,
            $priority,
            $dueDate !== '' ? $dueDate : null,
            'pending'
        );
    }

    header('Location: /index.php');
    exit;
}

// toggle task status
if (isset($_POST['action']) && $_POST['action'] === 'toggle_status') {
    $taskId = (int)($_POST['task_id'] ?? 0);
    $currentStatus = strtolower((string)($_POST['current_status'] ?? ''));
    $nextStatus = ($currentStatus === 'completed' || $currentStatus === 'done')
        ? 'pending'
        : 'completed';

    if ($taskId > 0) {
        $taskModel->updateStatus($taskId, $userId, $nextStatus);
    }

    header('Location: /index.php');
    exit;
}


$pageTitle = 'Dashboard';

if ($taskView === 'my') {
    $tasks = $taskModel->getIncompleteTasks($userId);
    $pageTitle = 'My Tasks';
} elseif ($taskView === 'completed') {
    $tasks = $taskModel->getCompletedTasks($userId);
    $pageTitle = 'Completed Tasks';
} else {
    $tasks = $taskModel->getAllTasks($userId);
}

require __DIR__ . '/../views/task/dashboard.php';
