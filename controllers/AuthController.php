<?php

require '../config/database.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $action = $_POST['action'];

    if ($action == 'register') {

        $name = $_POST['name'];
        $email = $_POST['email'];

        $password = password_hash(
            $_POST['password'],
            PASSWORD_DEFAULT
        );

        $query = "
            INSERT INTO users (
                name,
                email,
                password
            )
            VALUES (
                :name,
                :email,
                :password
            )
        ";

        $stmt = $conn->prepare($query);

        $stmt->execute([
            ':name' => $name,
            ':email' => $email,
            ':password' => $password
        ]);

        echo "Register Success";
    }
}