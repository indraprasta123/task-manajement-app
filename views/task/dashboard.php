<?php

$userName = $_SESSION['user_name'] ?? 'User';
$tasks = $tasks ?? [];

function formatTaskDate($value)
{
    if (!$value) {
        return '-';
    }

    return date('Y-m-d', strtotime($value));
}

function getPriorityClass($value)
{
    $priority = strtolower((string)$value);

    if ($priority === 'high' || $priority === 'hard') {
        return 'high';
    }

    if ($priority === 'medium') {
        return 'medium';
    }

    return 'low';
}

function getStatusClass($status, $dueDate)
{
    $value = strtolower((string)$status);

    if ($value === 'completed' || $value === 'done') {
        return 'done';
    }

    if ($dueDate && strtotime($dueDate) < strtotime(date('Y-m-d'))) {
        return 'overdue';
    }

    return 'pending';
}

?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="/css/global.css" />
    <link rel="stylesheet" href="/css/dashboard.css" />
    <title>Dashboard</title>
</head>

<body>
    <div class="dashboard-shell">
        <main class="main-content">
            <div class="topbar">
                <div class="user-info">
                    <div class="avatar"><?php echo strtoupper(substr($userName, 0, 1)); ?></div>
                    <div>
                        <div class="user-name"><?php echo htmlspecialchars($userName); ?></div>
                        <div class="user-subtitle">Welcome back</div>
                    </div>
                </div>
                <form method="post" action="/index.php">
                    <button class="logout-btn" type="submit" name="logout">Logout</button>
                </form>
            </div>

            <div class="card">
                <h3 class="section-title">Tasks</h3>
                <table>
                    <thead>
                        <tr>
                            <th>Task</th>
                            <th>Due Date</th>
                            <th>Priority</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (count($tasks) === 0) { ?>
                            <tr>
                                <td colspan="5">No tasks yet.</td>
                            </tr>
                        <?php } ?>
                        <?php foreach ($tasks as $task) { ?>
                            <?php
                            $priorityClass = getPriorityClass($task['priority'] ?? '');
                            $statusClass = getStatusClass($task['status'] ?? '', $task['due_date'] ?? null);
                            ?>
                            <tr>
                                <td data-label="Task"><?php echo htmlspecialchars($task['title'] ?? ''); ?></td>
                                <td data-label="Due Date"><?php echo formatTaskDate($task['due_date'] ?? null); ?></td>
                                <td data-label="Priority"><span class="badge <?php echo $priorityClass; ?>"><?php echo htmlspecialchars($task['priority'] ?? ''); ?></span></td>
                                <td data-label="Status"><span class="status <?php echo $statusClass; ?>"><?php echo htmlspecialchars($task['status'] ?? ''); ?></span></td>
                                <td data-label="Action">
                                    <form class="status-form" method="post" action="/index.php">
                                        <input type="hidden" name="action" value="toggle_status" />
                                        <input type="hidden" name="task_id" value="<?php echo (int)$task['id']; ?>" />
                                        <input type="hidden" name="current_status" value="<?php echo htmlspecialchars($task['status'] ?? ''); ?>" />
                                        <input class="status-toggle" type="checkbox" <?php echo $statusClass === 'done' ? 'checked' : ''; ?> />
                                    </form>
                                </td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </main>
    </div>

    <script src="/js/dashboard.js"></script>
</body>

</html>