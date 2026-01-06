<?php

require_once "C://xamppserver//htdocs//English//database//connect.php";

$msg = "";
if (isset($_POST['login'])) {
    $email = $_POST['email'];
    $pass = md5($_POST['password']);

    $sql = "SELECT * FROM user WHERE email='$email' AND password='$pass'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $_SESSION['user'] = $result->fetch_assoc();
        ($_SESSION['user']['role'] == 'admin') ? header("Location: http://localhost:8080/English/?module=admin&action=admin-page") : header("Location: http://localhost:8080/English/");
        exit;
    } else {
        $msg = "Email hoặc mật khẩu không chính xác!";
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <style>
    body {
        background: #c9d6ff;
        font-family: Arial;
    }

    .container {
        width: 350px;
        margin: 80px auto;
        padding: 30px;
        background: #fff;
        border-radius: 12px;
        box-shadow: 0 3px 10px rgba(0, 0, 0, 0.1);
    }

    input {
        width: 100%;
        padding: 12px;
        margin-top: 10px;
        border: 1px solid #ddd;
        border-radius: 8px;
    }

    button {
        width: 100%;
        padding: 12px;
        margin-top: 15px;
        background: #2563eb;
        color: #fff;
        border: none;
        border-radius: 8px;
        font-size: 16px;
        cursor: pointer;
    }

    a {
        color: #2563eb;
        text-decoration: none;
        font-size: 14px;
    }

    .error {
        color: red;
        margin-top: 10px;
        font-size: 14px;
    }
    </style>
</head>

<body>
    <div class="container">
        <h2>Welcome Back</h2>

        <form method="POST">
            <label>Email</label>
            <input type="email" name="email" placeholder="Enter your email" required>

            <label>Password</label>
            <input type="password" name="password" required>


            <button name="login">Login to your account</button>

            <?php if($msg != "") echo "<p class='error'>$msg</p>"; ?>

            <p>Don't have an account yet? <a
                    href="http://localhost:8080/English/?module=auth&action=register">Sign up
                    here</a>
            </p>
            <a href="index.php">Trang chủ</a>
        </form>
    </div>
</body>

</html>