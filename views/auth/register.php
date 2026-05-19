<?php

session_start();

if (isset($_SESSION['user_id'])) {

    header("Location: /index.php");
    exit;
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../css/global.css">
    <link rel="stylesheet" href="../../css/register.css">
    <title>Register</title>
</head>

<body>
    <div class="register">
        <div class="register-container">
            <div class="register-box">
                <div class="title-register-page">
                    <h1>Register</h1>
                </div>
                <div class="form-register-page">
                    <form
                        action="/routes/auth.php"
                        method="POST">
                        <div class="input-items">
                            <input
                                type="hidden"
                                name="action"
                                value="register">
                        </div>
                        <div class="input-items">
                            <input
                                type="text"
                                name="name"
                                placeholder="Name"
                                required>
                        </div>
                        <div class="input-items">
                            <input
                                type="email"
                                name="email"
                                placeholder="Email"
                                required>
                        </div>
                        <div class="input-items">
                            <input
                                type="password"
                                name="password"
                                placeholder="Password"
                                required>
                        </div>
                        <div class="button-items">
                            <button type="submit">
                                Register
                            </button>
                        </div>
                    </form>
                    <div class="register-account">
                        <p>Do you have an account?</p>
                        <a href="/views/auth/login.php">Login</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>