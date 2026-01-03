<?php
session_start();
require_once('../model/bookingModel.php');

if(!isset($_SESSION['customer_id'])){
    header('location: ../controller/login.php');
    exit();
}

$customerId = $_SESSION['customer_id'];
$customerName = isset($_SESSION['customer_name']) ? $_SESSION['customer_name'] : "Customer";

$recent = getRecentBookingsByCustomer($customerId, 5);

function h($s){ return htmlspecialchars($s); }

function statusClass($st){
    if($st == "Accepted") return "delivered";
    if($st == "Rejected") return "pending"; // simple fallback
    return "pending";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Customer Dashboard</title>
  <link rel="stylesheet" href="../asset/cdash-style.css">
</head>
<body>

<div class="navbar">
  <h2>Customer Dashboard</h2>
  <div class="nav-right">
    <span>Welcome, <?= h($customerName) ?>!</span>
    <a href="cprofile.php" title="Profile">
  <img src="../asset/port.jpg" class="avatar" width="40" height="40"></a>
    <a href="../controller/logout.php" class="logout">Logout</a>
  </div>
</div>

<div class="content">
  <div class="cards">
    <div class="card blue">
      <h3>Available Shipments</h3>
      <p>View available ships</p>
      <a href="booking.php">Book Now</a>
    </div>

    <div class="card orange">
      <h3>Shipment History</h3>
      <p>View past shipments</p>
      <a href="shipment.php">View History</a>
    </div>
  </div>

  <div class="table-box">
    <h3>Recent Shipments</h3>
    <table>
      <tr>
        <th>ID</th>
        <th>Origin</th>
        <th>Destination</th>
        <th>Cargo Type</th>
        <th>Status</th>
      </tr>

      <?php foreach($recent as $b){
            $origin = "";
            $route = isset($b['route']) ? $b['route'] : "";
            if($route != ""){
                $origin = $route;
            }
      ?>
      <tr>
        <td><?= $b['id'] ?></td>
        <td><?= h($origin) ?></td>
        <td><?= h($b['destination']) ?></td>
        <td><?= h($b['cargo_type']) ?></td>
        <td><span class="status <?= statusClass($b['status']) ?>"><?= h($b['status']) ?></span></td>
      </tr>
      <?php } ?>

      <?php if(count($recent) == 0){ ?>
      <tr>
        <td colspan="5">No bookings yet.</td>
      </tr>
      <?php } ?>
    </table>
  </div>

</div>

</body>
</html>
