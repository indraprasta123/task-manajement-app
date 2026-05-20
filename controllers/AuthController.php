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
        $_SESSION['flash_error'] = 'All fields are required.';
        header('Location: /views/auth/register.php');
        exit;
    }

    $passwordHash = password_hash($password, PASSWORD_DEFAULT);

    if ($userModel->createUser($name, $email, $passwordHash)) {
        $_SESSION['flash_success'] = 'Registration successful. Please log in.';
        header('Location: /views/auth/login.php');
        exit;
    }

    $_SESSION['flash_error'] = 'Registration failed.';
    header('Location: /views/auth/register.php');
    exit;
}

//handle login

if ($action === 'login') {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($email === '' || $password === '') {
        $_SESSION['flash_error'] = 'Email and password are required.';
        header('Location: /views/auth/login.php');
        exit;
    }

    $user = $userModel->findByEmail($email);

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_name'] = $user['name'];
        $_SESSION['user_email'] = $user['email'];
        $_SESSION['auth_token'] = bin2hex(random_bytes(32));

        setcookie('auth_token', $_SESSION['auth_token'], 0, '/');
        $_SESSION['flash_success'] = 'Login successful.';

        header('Location: /index.php');
        exit;
    }

    $_SESSION['flash_error'] = 'Invalid email or password.';
    header('Location: /views/auth/login.php');
    exit;
}

$_SESSION['flash_error'] = 'Invalid action.';
header('Location: /views/auth/login.php');
exit;
