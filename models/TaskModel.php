<?php

class TaskModel
{
    private PDO $conn;

    public function __construct(PDO $conn)
    {
        $this->conn = $conn;
    }

    public function getSummaryCounts(int $userId): array
    {
        $query = "
            SELECT
                SUM(CASE WHEN status NOT IN ('completed', 'done') THEN 1 ELSE 0 END) AS pending,
                SUM(CASE WHEN status IN ('completed', 'done') THEN 1 ELSE 0 END) AS completed,
                SUM(
                    CASE
                        WHEN due_date IS NOT NULL
                            AND due_date < CURRENT_DATE
                            AND status NOT IN ('completed', 'done')
                        THEN 1
                        ELSE 0
                    END
                ) AS overdue
            FROM tasks
            WHERE user_id = :user_id
        ";

        $stmt = $this->conn->prepare($query);
        $stmt->execute([':user_id' => $userId]);

        $result = $stmt->fetch(PDO::FETCH_ASSOC) ?: [];

        return [
            'pending' => (int)($result['pending'] ?? 0),
            'completed' => (int)($result['completed'] ?? 0),
            'overdue' => (int)($result['overdue'] ?? 0)
        ];
    }

    public function getAllTasks(int $userId, int $limit = 10, int $offset = 0): array
    {
        $query = "
            SELECT id, title, description, priority, due_date, status, created_at
            FROM tasks
            WHERE user_id = :user_id
            ORDER BY due_date ASC NULLS LAST, created_at DESC
            LIMIT :limit OFFSET :offset
        ";

        $stmt = $this->conn->prepare($query);
        $stmt->bindValue(':user_id', $userId, PDO::PARAM_INT);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getTotalTasks(int $userId): int
    {
        $query = "
            SELECT COUNT(*) as total
            FROM tasks
            WHERE user_id = :user_id
        ";

        $stmt = $this->conn->prepare($query);
        $stmt->execute([':user_id' => $userId]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        return (int)($result['total'] ?? 0);
    }

    public function getTodayTasks(int $userId): array
    {
        $query = "
            SELECT id, title, description, priority, due_date, status, created_at
            FROM tasks
            WHERE user_id = :user_id
                AND due_date = CURRENT_DATE
            ORDER BY due_date ASC, created_at DESC
        ";

        $stmt = $this->conn->prepare($query);
        $stmt->execute([':user_id' => $userId]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getIncompleteTasks(int $userId, int $limit = 10, int $offset = 0): array
    {
        $query = "
            SELECT id, title, description, priority, due_date, status, created_at
            FROM tasks
            WHERE user_id = :user_id
                AND status NOT IN ('completed', 'done')
            ORDER BY due_date ASC NULLS LAST, created_at DESC
            LIMIT :limit OFFSET :offset
        ";

        $stmt = $this->conn->prepare($query);
        $stmt->bindValue(':user_id', $userId, PDO::PARAM_INT);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getTotalIncompleteTasks(int $userId): int
    {
        $query = "
            SELECT COUNT(*) as total
            FROM tasks
            WHERE user_id = :user_id
                AND status NOT IN ('completed', 'done')
        ";

        $stmt = $this->conn->prepare($query);
        $stmt->execute([':user_id' => $userId]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        return (int)($result['total'] ?? 0);
    }

    //get completed tasks
    public function getCompletedTasks(int $userId, int $limit = 10, int $offset = 0): array
    {
        $query = "
            SELECT id, title, description, priority, due_date, status, created_at
            FROM tasks
            WHERE user_id = :user_id
                AND status IN ('completed', 'done')
            ORDER BY due_date ASC NULLS LAST, created_at DESC
            LIMIT :limit OFFSET :offset
        ";

        $stmt = $this->conn->prepare($query);
        $stmt->bindValue(':user_id', $userId, PDO::PARAM_INT);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getTotalCompletedTasks(int $userId): int
    {
        $query = "
            SELECT COUNT(*) as total
            FROM tasks
            WHERE user_id = :user_id
                AND status IN ('completed', 'done')
        ";

        $stmt = $this->conn->prepare($query);
        $stmt->execute([':user_id' => $userId]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        return (int)($result['total'] ?? 0);
    }

    public function updateStatus(int $taskId, int $userId, string $status): bool
    {
        $query = "
            UPDATE tasks
            SET status = :status
            WHERE id = :id AND user_id = :user_id
        ";

        $stmt = $this->conn->prepare($query);

        return $stmt->execute([
            ':status' => $status,
            ':id' => $taskId,
            ':user_id' => $userId
        ]);
    }

    public function createTask(
        int $userId,
        string $title,
        string $description,
        string $priority,
        ?string $dueDate,
        string $status
    ): bool {
        $query = "
            INSERT INTO tasks (user_id, title, description, priority, due_date, status)
            VALUES (:user_id, :title, :description, :priority, :due_date, :status)
        ";

        $stmt = $this->conn->prepare($query);

        return $stmt->execute([
            ':user_id' => $userId,
            ':title' => $title,
            ':description' => $description,
            ':priority' => $priority,
            ':due_date' => $dueDate,
            ':status' => $status
        ]);
    }

    public function deleteTask(int $taskId, int $userId): bool
    {
        $query = "
            DELETE FROM tasks
            WHERE id = :id AND user_id = :user_id
        ";

        $stmt = $this->conn->prepare($query);

        return $stmt->execute([
            ':id' => $taskId,
            ':user_id' => $userId
        ]);
    }

    //edit task
    public function getTaskById(int $taskId, int $userId): ?array
    {
        $query = "
            SELECT id, title, description, priority, due_date, status, created_at
            FROM tasks
            WHERE id = :id AND user_id = :user_id
        ";

        $stmt = $this->conn->prepare($query);
        $stmt->execute([':id' => $taskId, ':user_id' => $userId]);

        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result ?: null;
    }

    public function updateTask(
        int $taskId,
        int $userId,
        string $title,
        string $description,
        string $priority,
        ?string $dueDate
    ): bool {
        $query = "
            UPDATE tasks
            SET title = :title, description = :description, priority = :priority, due_date = :due_date
            WHERE id = :id AND user_id = :user_id
        ";

        $stmt = $this->conn->prepare($query);

        return $stmt->execute([
            ':id' => $taskId,
            ':user_id' => $userId,
            ':title' => $title,
            ':description' => $description,
            ':priority' => $priority,
            ':due_date' => $dueDate
        ]);
    }
}
