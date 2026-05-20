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
    session_start();
    $_SESSION['flash_success'] = 'Logout successful.';
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

    if ($title !== '' && $description !== '' && $priority !== '' && $dueDate !== '') {
        $taskModel->createTask(
            $userId,
            $title,
            $description,
            $priority,
            $dueDate,
            'pending'
        );
        $_SESSION['flash_success'] = 'Task added successfully.';
    } else {
        $_SESSION['flash_error'] = 'Task, description, priority, and due date are required.';
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
        $_SESSION['flash_success'] = 'Task status updated successfully.';
    }

    header('Location: /index.php?view=' . urlencode($taskView));
    exit;
}

// delete task
if (isset($_POST['action']) && $_POST['action'] === 'delete_task') {
    $taskId = (int)($_POST['task_id'] ?? 0);

    if ($taskId > 0) {
        $taskModel->deleteTask($taskId, $userId);
        $_SESSION['flash_success'] = 'Task deleted successfully.';
    }

    header('Location: /index.php?view=' . urlencode($taskView));
    exit;
}

// Pagination config
$itemsPerPage = 6;
$currentPage = max(1, (int)($_GET['page'] ?? 1));
$offset = ($currentPage - 1) * $itemsPerPage;

$pageTitle = 'Dashboard';
$totalTasks = 0;

if ($taskView === 'my') {
    $totalTasks = $taskModel->getTotalIncompleteTasks($userId);
    $tasks = $taskModel->getIncompleteTasks($userId, $itemsPerPage, $offset);
    $pageTitle = 'My Tasks';
} elseif ($taskView === 'completed') {
    $totalTasks = $taskModel->getTotalCompletedTasks($userId);
    $tasks = $taskModel->getCompletedTasks($userId, $itemsPerPage, $offset);
    $pageTitle = 'Completed Tasks';
} else {
    $totalTasks = $taskModel->getTotalTasks($userId);
    $tasks = $taskModel->getAllTasks($userId, $itemsPerPage, $offset);
}

$totalPages = ceil($totalTasks / $itemsPerPage);

require __DIR__ . '/../views/task/dashboard.php';
