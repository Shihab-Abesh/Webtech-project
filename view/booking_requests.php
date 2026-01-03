<?php
session_start();

require_once('../model/bookingModel.php');

if(!isset($_SESSION['owner_id'])){
    header('location: ../controller/login.php');
    exit();
}

$ownerId = $_SESSION['owner_id'];
$msg = "";

if(isset($_POST['action']) && isset($_POST['booking_id'])){
    $bookingId = intval($_POST['booking_id']);
    $action = $_POST['action'];

    if($action == "accept"){
        $ok = updateBookingStatus($bookingId, $ownerId, "Accepted");
        $msg = $ok ? "Booking accepted." : "Failed to accept booking.";
    }
    if($action == "reject"){
        $ok = updateBookingStatus($bookingId, $ownerId, "Rejected");
        $msg = $ok ? "Booking rejected." : "Failed to reject booking.";
    }
}

$bookings = getPendingBookingsByOwner($ownerId);

function h($s){ return htmlspecialchars($s); }
?>
<!DOCTYPE html>
<html>
<head>
  <title>Booking Requests</title>
  <link rel="stylesheet" href="../asset/common.css">
  <link rel="stylesheet" href="../asset/components.css">
  <link rel="stylesheet" href="../asset/tables.css">
  <script src="../asset/booking_requests.js"></script>
</head>
<body>
  <div class="page">
    <div class="topbar">
      <a class="back-link" href="owner_home.php">
        <img src="../asset/Back34.jpg" alt="Back"> Back
      </a>
      <div style="color:#fff; font-weight:bold;">Booking Requests</div>
    </div>

    <div class="card">
      <?php if($msg != ""){ ?>
        <div class="notice"><?= h($msg) ?></div>
      <?php } ?>

      <div class="filters">
        <input type="text" id="bookingSearch" placeholder="Search by customer or ship..." onkeyup="filterBookings()">
        <select id="statusFilter" onchange="filterBookings()">
          <option value="">All</option>
          <option value="Pending">Pending</option>
          <option value="Accepted">Accepted</option>
          <option value="Rejected">Rejected</option>
        </select>
      </div>

      <div class="table-wrap">
        <table id="bookingTable">
          <thead>
            <tr>
              <th>ID</th>
              <th>Ship</th>
              <th>Customer</th>
              <th>Destination</th>
              <th>Cargo</th>
              <th>Amount</th>
              <th>Status</th>
              <th>Requested</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach($bookings as $b){ ?>
              <tr>
                <td><?= $b['id'] ?></td>
                <td><?= h($b['ship_name']) ?></td>
                <td><?= h($b['customer_name']) ?></td>
                <td><?= h($b['destination']) ?></td>
                <td><?= h($b['cargo_type']) ?></td>
                <td>৳<?= number_format($b['amount'],2) ?></td>
                <td><span class="badge status-pending"><?= h($b['status']) ?></span></td>
                <td><?= h($b['requested_at']) ?></td>
                <td>
                  <form method="post" style="display:inline;" onsubmit="return confirmAccept()">
                    <input type="hidden" name="booking_id" value="<?= $b['id'] ?>">
                    <input type="hidden" name="action" value="accept">
                    <button class="btn btn-small btn-primary" type="submit">Accept</button>
                  </form>

                  <form method="post" style="display:inline;" onsubmit="return confirmReject()">
                    <input type="hidden" name="booking_id" value="<?= $b['id'] ?>">
                    <input type="hidden" name="action" value="reject">
                    <button class="btn btn-small btn-danger" type="submit">Reject</button>
                  </form>
                </td>
              </tr>
            <?php } ?>

            <?php if(count($bookings) == 0){ ?>
              <tr><td colspan="9">No pending booking requests.</td></tr>
            <?php } ?>
          </tbody>
        </table>
      </div>

    </div>
  </div>
</body>
</html>
