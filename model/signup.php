<?php
session_start();
include "db.php";

if (isset($_POST['register'])) {

    $role = $_POST['role'] ?? '';
    $fullname = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirmPassword = $_POST['confirmPassword'];
    $phone = $_POST['num'];
    $dob = $_POST['dob'];

    if (empty($fullname) || empty($email) || empty($password) ||
        empty($confirmPassword) || empty($phone) || empty($dob)) {
        die("All fields are required");
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        die("Invalid email format");
    }

    if ($password !== $confirmPassword) {
        die("Passwords do not match");
    }

    if (strlen($phone) !== 11) {
        die("Phone number must be 11 digits");
    }

    if ($role !== 'customer' && $role !== 'ship owner') {
        die("Please select a valid role");
    }

    $birthDate = new DateTime($dob);
    $today = new DateTime();
    $age = $today->diff($birthDate)->y;

    if ($age <= 20) {
        die("You must be older than 20 years");
    }

    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    
    $sql = "INSERT INTO `signup` (fullname, email, password, phone, dob, role)
            VALUES ('$fullname', '$email', '$hashedPassword', '$phone', '$dob', '$role')";

    
    if (mysqli_query($conn, $sql)) {
        header("Location: ../controller/login.html");
        exit;
    } else {
        if (mysqli_errno($conn) == 1062) {
            echo "This email is already registered";
        } else {
            echo "Database error: " . mysqli_error($conn);
        }
    }
}
?>
