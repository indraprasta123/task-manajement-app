<?php

session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: /views/auth/login.php');
    exit;
}

require __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../models/TaskModel.php';

$taskModel = new TaskModel($conn);
$userId = (int)$_SESSION['user_id'];
$taskId = (int)($_GET['id'] ?? 0);

// Get task data
$task = $taskModel->getTaskById($taskId, $userId);

if (!$task) {
    header('Location: /index.php');
    exit;
}

// Handle update
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'update_task') {
    $title = trim($_POST['title'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $priority = trim($_POST['priority'] ?? '');
    $dueDate = trim($_POST['due_date'] ?? '');

    if ($title !== '' && $description !== '' && $priority !== '' && $dueDate !== '') {
        $taskModel->updateTask(
            $taskId,
            $userId,
            $title,
            $description,
            $priority,
            $dueDate
        );

        header('Location: /index.php');
        exit;
    } else {
        $_SESSION['flash_error'] = 'Task, description, priority, dan due date wajib diisi.';
    }
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="/css/global.css" />
    <link rel="stylesheet" href="/css/add-task.css" />
    <link rel="stylesheet" href="/css/footer.css" />
    <title>Edit Task</title>
</head>

<body>
    <div class="add-task">
        <div class="add-task-container">
            <div class="page-header">
                <div>
                    <h1>Edit Task</h1>
                    <p>Update the form below to modify the task.</p>
                </div>
                <a class="btn-secondary" href="/index.php">Back</a>
            </div>

            <div class="add-task-card">
                <form class="add-task-form" method="POST" data-swal-validate="true" data-swal-context="edit" novalidate>
                    <input type="hidden" name="action" value="update_task" />

                    <div class="form-group">
                        <label for="title">Task</label>
                        <input id="title" name="title" type="text" placeholder="Enter task name" value="<?php echo htmlspecialchars($task['title'] ?? ''); ?>" required />
                    </div>

                    <div class="form-group">
                        <label for="description">Description</label>
                        <textarea id="description" name="description" rows="4" placeholder="Short description" required><?php echo htmlspecialchars($task['description'] ?? ''); ?></textarea>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="priority">Priority</label>
                            <select id="priority" name="priority" required>
                                <option value="" disabled>Select priority</option>
                                <option value="Low" <?php echo ($task['priority'] === 'Low') ? 'selected' : ''; ?>>Low</option>
                                <option value="Medium" <?php echo ($task['priority'] === 'Medium') ? 'selected' : ''; ?>>Medium</option>
                                <option value="High" <?php echo ($task['priority'] === 'High') ? 'selected' : ''; ?>>High</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="due_date">Due Date</label>
                            <input id="due_date" name="due_date" type="date" value="<?php echo htmlspecialchars($task['due_date'] ?? ''); ?>" required />
                        </div>
                    </div>

                    <div class="form-actions">
                        <button class="btn-primary" type="submit">Update Task</button>
                        <a class="btn-secondary" href="/index.php">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <?php require __DIR__ . '/../layouts/footer.php'; ?>
</body>

</html>