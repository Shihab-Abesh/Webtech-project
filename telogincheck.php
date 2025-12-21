<?php
session_start();

$userid = $_POST['userid'];
$password = $_POST['password'];

if ($userid == "" || $password == "") {
    echo "Empty field found";
    echo "<br><a href='telogin.php'>Go Back</a>";
    exit();
}

/* Admin Login */
if ($userid == "ADM001" && $password == "admin") {
    $_SESSION['userid'] = $userid;
    $_SESSION['role'] = "admin";
    echo "<h2>Admin Dashboard</h2>";
    echo "Welcome Admin<br>";
    echo "<a href='telogout.php'>Logout</a>";
}

/* Customer Login */
elseif ($userid == "CUS001" && $password == "customer") {
    $_SESSION['userid'] = $userid;
    $_SESSION['role'] = "customer";
    echo "<h2>Customer Dashboard</h2>";
    echo "Welcome Customer<br>";
    echo "<a href='telogout.php'>Logout</a>";
}

/* Ship Owner Login */
elseif ($userid == "OWN001" && $password == "owner") {
    $_SESSION['userid'] = $userid;
    $_SESSION['role'] = "owner";
    echo "<h2>Ship Owner Dashboard</h2>";
    echo "Welcome Ship Owner<br>";
    echo "<a href='telogout.php'>Logout</a>";
}

else {
    echo "Invalid User ID or Password";
    echo "<br><a href='telogin.php'>Try Again</a>";
}
?>
