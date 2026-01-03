<?php
session_start();
require_once('../model/bookingModel.php');

if(!isset($_SESSION['owner_id'])){
    header('location: ../controller/login.php');
    exit();
}

$ownerId = $_SESSION['owner_id'];
$items = getHistoryBookingsByOwner($ownerId);

function h($s){ return htmlspecialchars($s); }

function statusClass($st){
    if($st == "Accepted") return "status-accepted";
    if($st == "Rejected") return "status-rejected";
    return "status-pending";
}
?>
<!DOCTYPE html>
<html>
<head>
  <title>Owner Booking History</title>
  <link rel="stylesheet" href="../asset/common.css">
  <link rel="stylesheet" href="../asset/components.css">
  <link rel="stylesheet" href="../asset/tables.css">
  <link rel="stylesheet" href="../asset/owner_history.css">
  <script src="../asset/owner_history.js"></script>
</head>
<body>
  <div class="page">
    <div class="topbar">
      <a class="back-link" href="owner_home.php">
        <img src="../asset/Back34.jpg" alt="Back"> Back
      </a>
      <div style="color:#fff; font-weight:bold;">Booking History</div>
      <div style="margin-left:auto;" class="no-print">
        <a class="btn btn-neutral" style="text-decoration:none;" href="export_history.php">Export</a>
        <button class="btn btn-primary" onclick="window.print()">Print</button>
      </div>
    </div>

    <div class="card">
      <div class="filters no-print">
        <input type="text" id="historySearch" placeholder="Search..." onkeyup="filterHistory()">
        <select id="historyStatus" onchange="filterHistory()">
          <option value="">All</option>
          <option value="Pending">Pending</option>
          <option value="Accepted">Accepted</option>
          <option value="Rejected">Rejected</option>
        </select>
      </div>

      <div class="table-wrap">
        <table id="historyTable">
          <thead>
            <tr>
              <th>ID</th>
              <th>Ship</th>
              <th>Customer</th>
              <th>Destination</th>
              <th>Cargo</th>
              <th>Status</th>
              <th>Amount</th>
              <th>Requested</th>
            </tr>
          </thead>
          <tbody>
          <?php foreach($items as $b){ ?>
            <tr data-status="<?= h($b['status']) ?>">
              <td><?= $b['id'] ?></td>
              <td><?= h($b['ship_name']) ?></td>
              <td><?= h($b['customer_name']) ?></td>
              <td><?= h($b['destination']) ?></td>
              <td><?= h($b['cargo_type']) ?></td>
              <td><span class="badge <?= statusClass($b['status']) ?>"><?= h($b['status']) ?></span></td>
              <td>৳<?= number_format($b['amount'],2) ?></td>
              <td><?= h($b['requested_at']) ?></td>
            </tr>
          <?php } ?>

          <?php if(count($items) == 0){ ?>
            <tr><td colspan="8">No history found.</td></tr>
          <?php } ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</body>
</html>
