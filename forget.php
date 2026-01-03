<?php
session_start();
require_once('../model/db.php');
$con = getConnection();

$msg = '';


if (isset($_POST['reset'])) {

    $email = $_POST['email'];
    $newPass = $_POST['password'];
    $confirmPass = $_POST['confirmpassword'];

    if($email == '' || $newPass == '' || $confirmPass == ''){
        $msg = 'Null submission!';
    }
    
    if ($newPass !== $confirmPass) {
        echo "<script>
                alert('Passwords do not match');
                window.location.href = 'forget.php';
              </script>";
        exit();
    }

    $checkEmail = $con->prepare("SELECT id FROM users WHERE email = ?");
    $checkEmail->bind_param("s", $email);
    $checkEmail->execute();
    $checkEmail->store_result();

    if ($checkEmail->num_rows === 0) {
        echo "<script>
                alert('Email not found');
                window.location.href = 'forget.php';
              </script>";
        exit();
    }


    $hashedPassword = $newPass;

    
    $update = $con->prepare("UPDATE users SET password = ? WHERE email = ?");
    $update->bind_param("ss", $hashedPassword, $email);

    if ($update->execute()) {
        echo "<script>
                alert('Password reset successful');
                window.location.href = 'login.php';
              </script>";
        exit();
    } else {
        echo "<script>
                alert('Something went wrong');
                window.location.href = 'forget.php';
              </script>";
        exit();
    }

    $checkEmail->close();
    $update->close();
}

mysqli_close($con);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Forgot Password</title>
    <link rel="stylesheet" href="../asset/forget-style.css">
</head>

<body>

    <div class="container">
        <div class="login-box">
            <h2>Forgot Password</h2>

            <p>Please enter your Email address to reset your password.</p>

            <form action="" method="POST">
                <label>Email</label>
                <input type="email" name="email" required>

                <label>New Password</label>
                <input type="password" name="password" required>

                <label>Confirm Password</label>
                <input type="password" name="confirmpassword" required>

                <button type="submit" name="reset">Reset Password</button>
                
            </form>

            <div class="links">
                <a href="login.php">Back to Login</a>
            </div>
        </div>
    </div>

</body>
</html>
