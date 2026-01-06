<?php
require_once "C://xamppserver//htdocs//English//database//connect.php";

$msg = "";

if (isset($_POST['register'])) {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $pass = md5($_POST['password']);
    $confirm = md5($_POST['confirm']);

    if ($pass !== $confirm) {
        $msg = "Mật khẩu xác nhận không đúng!";
    } else {
        $check = $conn->query("SELECT * FROM user WHERE email='$email'");
        if ($check->num_rows > 0) {
            $msg = "Email đã tồn tại!";
        } else {
            $sql = "INSERT INTO user(username,email,password,role) VALUES('$username','$email','$pass','học viên')";
            if ($conn->query($sql)) {
                header("Location: http://localhost:8080/English/?module=auth&action=login");
                exit;
            } else {
                $msg = "Lỗi khi tạo tài khoản!";
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Register</title>
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

    input[type="text"],
    input[type="email"],
    input[type="password"] {
        width: 100%;
        padding: 12px;
        margin-top: 10px;
        border: 1px solid #ddd;
        border-radius: 8px;
        box-sizing: border-box;
    }

    input[type="checkbox"] {
        width: auto;
        margin: 0 8px 0 0;
        vertical-align: middle;
    }


    label.checkbox-label {
        display: flex;
        align-items: center;
        margin-top: 10px;
        cursor: pointer;
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
        <h2>Create an Account</h2>

        <form method="POST">
            <label>Username</label>
            <input type="text" name="username" required>

            <label>Email</label>
            <input type="email" name="email" required>

            <label>Password</label>
            <input type="password" name="password" required>

            <label>Confirm Password</label>
            <input type="password" name="confirm" required>

            <label class="checkbox-label"><input type="checkbox" required> I accept the Terms and
                Conditions</label>

            <button name="register">Login to your account</button>

            <?php if($msg != "") echo "<p class='error'>$msg</p>"; ?>

            <p>Already have an account? <a
                    href="http://localhost:8080/English/?module=auth&action=login">Login here</a>
            </p>
        </form>
    </div>
</body>

</html>