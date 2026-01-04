<?php
session_start();
require_once('../model/userModel.php');
require_once('../model/adminModel.php');

$msg = "";

if(isset($_POST['login'])){

    $email = $_POST['email'];
    $password = $_POST['password'];

    if($email == "" || $password == ""){
         header('location: login.php');
        $msg = "null submission!";
    }else{

       
        $admin = ['admin_id'=>$email, 'password'=>$password];
        if(adminLogin($admin)){
            $_SESSION['admin_id'] = $email;
            header('location:../view/admin_dashboard.php');
            exit();
        }

        $user = ['email'=>$email, 'password'=>$password];
        $row = login($user);

        if($row){

            if($row['role'] == 'customer'){
                $_SESSION['customer_id'] = $row['id'];
                $_SESSION['customer_name'] = $row['name'];
                $_SESSION['customer_email'] = $row['email'];
                $_SESSION['customer_role'] = $row['role'];

                header('location:../view/cdashboard.php');
                exit();
            }

            $_SESSION['owner_id'] = $row['id'];
            $_SESSION['owner_name'] = $row['name'];
            $_SESSION['owner_email'] = $row['email'];
            $_SESSION['owner_role'] = $row['role'];

            header('location: ../view/owner_home.php');
            exit();

        }else{
            $msg = "invalid user!";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login Page</title>
  <link rel="stylesheet" href="../asset/login-style.css">

</head>
<h1>Shipping Port Management System</h1>

<body>

    <div class="container">
        <div class="login-box">
            <h2>Login</h2>

            <form method="POST" action="login.php">
                <label>Email</label>
                <input type="email" name="email" required>

                <label>Password</label>
                <input type="password" name="password" required>

                <button type="submit" name="login">Login</button>
            </form>
            <?php if($msg != ""){ ?>
              <div class="links" style="margin-top:12px; color:red;"><b><?php echo $msg; ?></b></div>
            <?php } ?>

          <div class="links">
              <a href="signup.php">Sign Up</a>
              <span>|</span>
              <a href="forget.php">Forgot Password?</a>
          </div>
        
        </div>
    </div>


</body>
</html>
