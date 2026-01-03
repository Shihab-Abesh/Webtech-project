<?php
session_start();

require_once('../model/shipModel.php');
require_once('../model/bookingModel.php');
require_once('../model/withdrawModel.php');
require_once('../model/userModel.php');

if(!isset($_SESSION['owner_id'])){
    header('location: ../controller/login.php');
    exit();
}

$ownerId = $_SESSION['owner_id'];
$ownerName = isset($_SESSION['owner_name']) ? $_SESSION['owner_name'] : "Owner";

$balance = getBalance($ownerId);

$ships_total = countShipsByOwner($ownerId);
$ships_active = countActiveShipsByOwner($ownerId);

$pending  = countBookingsByOwnerAndStatus($ownerId, "Pending");
$accepted = countBookingsByOwnerAndStatus($ownerId, "Accepted");
$rejected = countBookingsByOwnerAndStatus($ownerId, "Rejected");

$withdraw_count = countWithdrawalsByOwner($ownerId);
?>
<!DOCTYPE html>
<html>
<head>
  <title>Owner Home</title>
  <link rel="stylesheet" href="../asset/common.css">
  <link rel="stylesheet" href="../asset/components.css">
  <script src="../asset/owner_home.js"></script>
</head>
<body>
  <div class="page">
    <div class="topbar">
      <div style="color: blue; font-weight:bold;">Owner Dashboard</div>
    
      <a href="profile.php" class="profile-btn" title="Profile" style="margin-left:auto;">
        <img src="../asset/Profile.png" alt="Profile">
      </a>
    
    </div>

    <div class="cards">
      <div class="summary">
        <div class="label">Total Ships</div>
        <div class="value"><?= $ships_total ?></div>
      </div>
      <div class="summary">
        <div class="label">Active Ships</div>
        <div class="value"><?= $ships_active ?></div>
      </div>
      <div class="summary">
        <div class="label">Pending Bookings</div>
        <div class="value"><?= $pending ?></div>
      </div>
      <div class="summary">
        <div class="label">Accepted Bookings</div>
        <div class="value"><?= $accepted ?></div>
      </div>
      <div class="summary">
        <div class="label">Rejected Bookings</div>
        <div class="value"><?= $rejected ?></div>
      </div>
      <div class="summary">
        <div class="label">Withdraw Requests</div>
        <div class="value"><?= $withdraw_count ?></div>
      </div>
      <div class="summary">
        <div class="label">Available Balance</div>
        <div class="value">৳<?= number_format($balance,2) ?></div>
      </div>
    </div>

    <div class="card">
      <h2 style="margin-top:0;">Welcome, <?= htmlspecialchars($ownerName) ?></h2>
      <div class="nav">
        <a href="add_ship.php">Add New Ship</a>
        <a href="edit_ship.php">Update Ship Details</a>
        <a href="booking_requests.php">Booking Requests</a>
        <a href="owner_history.php">Booking History</a>
        <a href="withdraw.php">Withdraw Payment</a>
        <a href="../controller/logout.php" onclick="return confirmLogout()">Logout</a>
      </div>
    </div>
  </div>
</body>
</html>
