<?php
session_start();
require_once('../model/userModel.php');

$msg = "";

if(isset($_POST['register'])){
    $role = $_POST['role'];
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirm = $_POST['confirmPassword'];
    $phone = $_POST['num'];
    $dob = $_POST['dob'];

    if($role=="" || $name=="" || $email=="" || $password=="" || $confirm=="" || $phone=="" || $dob==""){
        $msg = "null submission!";
    }else if($password != $confirm){
        $msg = "password not matched!";
    }else{
      $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $user = ['role'=>$role, 'name'=>$name, 'email'=>$email, 'password'=>$hashedPassword, 'phone'=>$phone, 'dob'=>$dob];
        $status = addUser($user);
$status = addUser($user);
        if($status === true){
            header('location: ../controller/login.php');
            exit();
        }
        elseif($status === "email_exists"){
            $msg = "email already registered!!!!";
         }
         else{
           $msg = "signup failed!";
        }
    }
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Registration Form</title>
  <link rel="stylesheet" href="../asset/signup-style.css">
</head>
<body>

  <div class="form-container">
    <h2>Sign Up</h2>

      <form method="POST" action="signup.php" onsubmit="return validateForm()">

      <label>Role</label>
      <select name="role" required>
      <option value="">-- Select Role --</option>
      <option value="customer">Customer</option>
      <option value="ship owner">Ship Owner</option>
      </select>


      <label>Full Name</label>
      <input type="text" id="name" name="name" required>

      <label>Email</label>
      <input type="email" id="email" name="email" required onkeyup="checkEmailAjax()">
      <span id="emailMsg" style="display:block; margin-top:6px; font-weight:bold;"></span>

      <label>Password</label>
      <input type="password" id="password" name="password" required>

      <label>Confirm Password</label>
      <input type="password" id="confirmPassword" name="confirmPassword" required>

      <label>Phone Number</label>
      <input type="tel" id="num" name="num" required>

      <label>DOB</label>
      <input type="date" id="dob" name="dob" required>

      <button type="submit" name="register">Register</button>

      <?php if($msg != ""){ ?>
        <p style="color:red; margin-top:10px;"><b><?php echo $msg; ?></b></p>
      <?php } ?>


    </form>
  </div>

  <script src="../asset/signup.js"></script>


</body>
</html>
