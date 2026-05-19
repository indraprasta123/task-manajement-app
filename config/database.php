<?php

$host = "localhost";
$port = "5432";
$dbname = "task_management";
$username = "postgres";
$password = "postgres";

try {

    $conn = new PDO(
        "pgsql:host=$host;port=$port;dbname=$dbname",
        $username,
        $password
    );

    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

} catch (PDOException $e) {

    die("Database Connection Failed : " . $e->getMessage());
}

