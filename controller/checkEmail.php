<?php
require_once('../model/db.php');

$email = $_POST['email'] ?? '';
$email = trim($email);
if($email == "")
    {
    echo "";
    exit();
   }
$con = getConnection();
$email = mysqli_real_escape_string($con, $email);

$sql = "SELECT id FROM users WHERE email='{$email}' LIMIT 1";
$result = mysqli_query($con, $sql);

if($result && mysqli_num_rows($result) == 1)
    {
    echo "<span style='color:red;'> Email already registered</span>";
} 
   else
     {
    echo "<span style='color:green;'> Email available</span>";
}
?>
