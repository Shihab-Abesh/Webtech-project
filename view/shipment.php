<?php
session_start();
require_once('../model/bookingModel.php');

if(!isset($_SESSION['customer_id'])){
    header('location: ../controller/login.php');
    exit();
}

$customerId = $_SESSION['customer_id'];
$items = getBookingsByCustomer($customerId);

function h($s){ return htmlspecialchars($s); }
function statusClass($st){
    if($st == "Accepted") return "Accepted";
    if($st == "Rejected") return "Rejected";
    return "Pending";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Shipment History</title>
    <link rel="stylesheet" href="../asset/shipment-style.css">
    <style>
      .stat.Accepted{color:green;font-weight:bold;}
      .stat.Rejected{color:red;font-weight:bold;}
      .stat.Pending{color:orange;font-weight:bold;}
    </style>
</head>
<body>

<div class="container">
    <h2>Booked Shipments History</h2>

    <table class="shipment-table">
        <thead>
            <tr>
                <th>Ship Name</th>
                <th>Destination</th>
                <th>Cargo Type</th>
                <th>Status</th>
                <th>Booking Date</th>
                <th>Amount</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($items as $b){ ?>
            <tr>
                <td><?= h($b['ship_name']) ?></td>
                <td><?= h($b['destination']) ?></td>
                <td><?= h($b['cargo_type']) ?></td>
                <td class="stat <?= statusClass($b['status']) ?>"><?= h($b['status']) ?></td>
                <td><?= h($b['requested_at']) ?></td>
                <td><?= number_format($b['amount'],2) ?>TK</td>
            </tr>
            <?php } ?>

            <?php if(count($items) == 0){ ?>
            <tr><td colspan="6">No booked shipments yet.</td></tr>
            <?php } ?>
        </tbody>
    </table>

    <div style="text-align:center;margin-top:18px;">
      <a href="cdashboard.php" style="color:#fff;text-decoration:none;background:#1e3a5f;padding:10px 14px;border-radius:10px;">Back</a>
    </div>
</div>

</body>
</html>
