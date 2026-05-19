<?php

require __DIR__ . '/../config/database.php';
require __DIR__ . '/../models/UserModel.php';

session_start();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo 'Method Not Allowed';
    exit;
}

$action = $_POST['action'] ?? '';

$userModel = new UserModel($conn);

//handle register

if ($action === 'register') {
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($name === '' || $email === '' || $password === '') {
        echo 'All fields are required';
        exit;
    }

    $passwordHash = password_hash($password, PASSWORD_DEFAULT);

    if ($userModel->createUser($name, $email, $passwordHash)) {
        header('Location: /views/auth/login.php');
        exit;
    }

    echo 'Register failed';
    exit;
}

//handle login

if ($action === 'login') {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($email === '' || $password === '') {
        echo 'Email and password are required';
        exit;
    }

    $user = $userModel->findByEmail($email);

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_name'] = $user['name'];
        $_SESSION['user_email'] = $user['email'];
        $_SESSION['auth_token'] = bin2hex(random_bytes(32));

        setcookie('auth_token', $_SESSION['auth_token'], 0, '/');

        header('Location: /index.php');
        exit;
    }

    echo 'Email or password is incorrect';
    exit;
}

echo 'Invalid action';
