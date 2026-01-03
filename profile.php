<?php
session_start();
require_once('../model/userModel.php');

if(!isset($_SESSION['owner_id'])){
    header('location: ../controller/login.php');
    exit();
}

$ownerId = $_SESSION['owner_id'];
$user = getUserById($ownerId);

if(!$user){
    echo "User not found!";
    exit();
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Owner Profile</title>
    <link rel="stylesheet" href="../asset/common.css">
    <link rel="stylesheet" href="../asset/components.css">
</head>
<body>
<div class="page">
    <div class="topbar">
        <a href="owner_home.php" class="back-link">Back</a>
        <div style="color: blue; font-weight:bold;">My Profile</div>
    </div>

    <div class="card" style="max-width:520px; margin:0 auto;">
        <h2 style="margin-top:0;">Profile Information</h2>

        <p><b>ID:</b> <?= $user['id'] ?></p>
        <p><b>Role:</b> <?= htmlspecialchars($user['role']) ?></p>
        <p><b>Full Name:</b> <?= htmlspecialchars($user['name']) ?></p>
        <p><b>Email:</b> <?= htmlspecialchars($user['email']) ?></p>
        <p><b>Phone:</b> <?= htmlspecialchars($user['phone']) ?></p>
        <p><b>Date of Birth:</b> <?= htmlspecialchars($user['dob']) ?></p>
        <p><b>Available Balance:</b> ৳<?= number_format($user['balance'], 2) ?></p>

    </div>
</div>
</body>
</html>
