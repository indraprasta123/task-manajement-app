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

    public function getAllTasks(int $userId): array
    {
        $query = "
            SELECT id, title, description, priority, due_date, status, created_at
            FROM tasks
            WHERE user_id = :user_id
            ORDER BY due_date ASC NULLS LAST, created_at DESC
        ";

        $stmt = $this->conn->prepare($query);
        $stmt->execute([':user_id' => $userId]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
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

    public function getIncompleteTasks(int $userId): array
    {
        $query = "
            SELECT id, title, description, priority, due_date, status, created_at
            FROM tasks
            WHERE user_id = :user_id
                AND status NOT IN ('completed', 'done')
            ORDER BY due_date ASC NULLS LAST, created_at DESC
        ";

        $stmt = $this->conn->prepare($query);
        $stmt->execute([':user_id' => $userId]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getCompletedTasks(int $userId): array
    {
        $query = "
            SELECT id, title, description, priority, due_date, status, created_at
            FROM tasks
            WHERE user_id = :user_id
                AND status IN ('completed', 'done')
            ORDER BY due_date ASC NULLS LAST, created_at DESC
        ";

        $stmt = $this->conn->prepare($query);
        $stmt->execute([':user_id' => $userId]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
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
}
