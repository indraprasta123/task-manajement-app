<aside class="sidebar">
    <div class="sidebar-brand">Task Manager</div>

    <a class="sidebar-add-btn" href="/routes/task.php?view=add">Add Task</a>

    <nav class="sidebar-nav">
        <a class="sidebar-link <?php echo ($taskView ?? 'dashboard') === 'dashboard' ? 'active' : ''; ?>" href="/routes/task.php?view=dashboard">Dashboard</a>
        <a class="sidebar-link <?php echo ($taskView ?? '') === 'my' ? 'active' : ''; ?>" href="/routes/task.php?view=my">My Tasks</a>
        <a class="sidebar-link <?php echo ($taskView ?? '') === 'completed' ? 'active' : ''; ?>" href="/routes/task.php?view=completed">Completed</a>
    </nav>
</aside>