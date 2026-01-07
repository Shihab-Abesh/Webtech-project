<?php
session_start();
require_once('../model/userModel.php');
require_once('../model/adminModel.php');

if (!isset($_SESSION['admin_id']) && isset($_COOKIE['login_email']) && isset($_COOKIE['login_role'])) {
   if ($_COOKIE['login_role'] === 'admin') {
       $_SESSION['admin_id'] = $_COOKIE['login_email'];
       header('location:../view/admin_dashboard.php');
       exit();
   }
   if ($_COOKIE['login_role'] === 'customer') {
       $_SESSION['customer_email'] = $_COOKIE['login_email'];
       header('location:../view/cdashboard.php');
       exit();
   }
   if ($_COOKIE['login_role'] === 'ship owner') {
       $_SESSION['owner_email'] = $_COOKIE['login_email'];
       header('location:../view/owner_home.php');
       exit();
   }
}

$msg = "";

if(isset($_POST['login'])){

    $email = $_POST['email'];
    $password = $_POST['password'];

    if($email == "" || $password == ""){
        $msg = "null submission!";
    }else{

       
        $admin = ['admin_id'=>$email, 'password'=>$password];
        if(adminLogin($admin)){
            $_SESSION['admin_id'] = $email;
            header('location:../view/admin_dashboard.php');
            setcookie('login_email', $email, time() + (30), "/");
            setcookie('login_role', 'admin', time() + (30), "/");
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
                setcookie('login_email', $row['email'], time() + (30), "/");
                setcookie('login_role', 'customer', time() + (30), "/");

                header('location:../view/cdashboard.php');
                exit();
            }
           if($row['role'] == 'ship owner'){
            $_SESSION['owner_id'] = $row['id'];
            $_SESSION['owner_name'] = $row['name'];
            $_SESSION['owner_email'] = $row['email'];
            $_SESSION['owner_role'] = $row['role'];
            setcookie('login_email', $row['email'], time() + (30), "/");
            setcookie('login_role', 'ship owner', time() + (30), "/");


            header('location: ../view/owner_home.php');
            exit();

        }
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
