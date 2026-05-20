<?php

session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: /views/auth/login.php');
    exit;
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
    <title>Add Task</title>
</head>

<body>
    <div class="add-task">
        <div class="add-task-container">
            <div class="page-header">
                <div>
                    <h1>Add Task</h1>
                    <p>Fill out the form below to create a new task.</p>
                </div>
                <a class="btn-secondary" href="/index.php">Back</a>
            </div>

            <div class="add-task-card">
                <form class="add-task-form" method="POST" action="/routes/task.php" data-swal-validate="true" data-swal-context="add" novalidate>
                    <input type="hidden" name="action" value="create_task" />

                    <div class="form-group">
                        <label for="title">Task</label>
                        <input id="title" name="title" type="text" placeholder="Enter task name" required />
                    </div>

                    <div class="form-group">
                        <label for="description">Description</label>
                        <textarea id="description" name="description" rows="4" placeholder="Short description" required></textarea>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="priority">Priority</label>
                            <select id="priority" name="priority" required>
                                <option value="" disabled selected>Select priority</option>
                                <option value="Low">Low</option>
                                <option value="Medium">Medium</option>
                                <option value="High">High</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="due_date">Due Date</label>
                            <input id="due_date" name="due_date" type="date" required />
                        </div>
                    </div>

                    <div class="form-actions">
                        <button class="btn-primary" type="submit">Save Task</button>
                        <a class="btn-secondary" href="/index.php">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <?php require __DIR__ . '/../layouts/footer.php'; ?>
</body>

</html>