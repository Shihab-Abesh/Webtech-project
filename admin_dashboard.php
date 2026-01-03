<?php
require_once('admin_guard.php');
require_once('../model/adminModel.php');

function h($s){ return htmlspecialchars($s); }

$totalUsers = countUsers();
$totalOwners = countOwners();
$totalCustomers = countCustomers();
$totalShips = countShips();

$pendingShipCount = countPendingShips();
$pendingBookingCount = countPendingBookings();

$pendingShips = getPendingShipsAdmin(8);
$pendingBookings = getPendingBookingsAdmin(8);
?>
<!DOCTYPE html>
<html>
<head>
  <title>Admin Dashboard</title>
  <link rel="stylesheet" href="../asset/common.css">
  <link rel="stylesheet" href="../asset/components.css">
  <link rel="stylesheet" href="../asset/admin-style.css">
</head>
<body>
  <div class="page">
    <div class="topbar">
      <div style="color: blue; font-weight:bold;">Admin Dashboard</div>
      <a href="../controller/admin_logout.php" style="margin-left:auto;" class="back-link">Logout</a>
    </div>

    <div class="admin-grid">
      <div class="admin-card" onclick="window.location.href='admin_manage_shipments.php'">
        <h3>Manage Shipments</h3>
        <p>Approve / activate / delete ships</p>
      </div>

      <div class="admin-card" onclick="window.location.href='admin_manage_users.php'">
        <h3>Manage Users</h3>
        <p>View and delete users</p>
      </div>

      <div class="admin-card" onclick="window.location.href='admin_pending_approvals.php'">
        <h3>Pending Approvals</h3>
        <p>Inactive ships: <?= $pendingShipCount ?></p>
      </div>
    </div>

    <div class="card">
      <h3 style="margin-top:0;">User Overview</h3>
      <div class="table-wrap">
        <table>
          <tr>
            <th>Total Users</th>
            <th>Ship Owners</th>
            <th>Customers</th>
            <th>Total Ships</th>
            <th>Pending Bookings</th>
          </tr>
          <tr>
            <td><?= $totalUsers ?></td>
            <td><?= $totalOwners ?></td>
            <td><?= $totalCustomers ?></td>
            <td><?= $totalShips ?></td>
            <td><?= $pendingBookingCount ?></td>
          </tr>
        </table>
      </div>
    </div>

    <div class="card" style="margin-top:12px;">
      <h3 style="margin-top:0;">Latest Pending Ship Approvals</h3>
      <div class="table-wrap">
        <table>
          <tr>
            <th>ID</th><th>Owner</th><th>Ship</th><th>Capacity</th><th>Route</th><th>Fee</th><th>Action</th>
          </tr>

          <?php if(count($pendingShips) == 0){ ?>
            <tr><td colspan="7">No pending ships.</td></tr>
          <?php } else { foreach($pendingShips as $s){ ?>
            <tr>
              <td><?= $s['id'] ?></td>
              <td><?= h($s['owner_name']) ?> (<?= h($s['owner_email']) ?>)</td>
              <td><?= h($s['name']) ?></td>
              <td><?= $s['capacity'] ?></td>
              <td><?= h($s['route']) ?></td>
              <td>৳<?= number_format($s['fee'],2) ?></td>
              <td>
                <a href="admin_pending_approvals.php?approve=<?= $s['id'] ?>" onclick="return confirm('Approve this ship?')">
                  <button class="btn-small btn-approve">Approve</button>
                </a>
                <a href="admin_pending_approvals.php?reject=<?= $s['id'] ?>" onclick="return confirm('Reject and delete this ship?')">
                  <button class="btn-small btn-delete">Reject</button>
                </a>
              </td>
            </tr>
          <?php }} ?>
        </table>
      </div>
    </div>

    <div class="card" style="margin-top:12px;">
      <h3 style="margin-top:0;">Latest Pending Booking Requests</h3>
      <div class="table-wrap">
        <table>
          <tr>
            <th>ID</th><th>Customer</th><th>Ship</th><th>Amount</th><th>Status</th>
          </tr>
          <?php if(count($pendingBookings) == 0){ ?>
            <tr><td colspan="5">No pending bookings.</td></tr>
          <?php } else { foreach($pendingBookings as $b){ ?>
            <tr>
              <td><?= $b['id'] ?></td>
              <td><?= h($b['customer_name']) ?> (<?= h($b['customer_email']) ?>)</td>
              <td><?= h($b['ship_name']) ?></td>
              <td>৳<?= number_format($b['amount'],2) ?></td>
              <td><span class="status pending"><?= h($b['status']) ?></span></td>
            </tr>
          <?php }} ?>
        </table>
      </div>
    </div>

  </div>
</body>
</html>
