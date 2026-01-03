<?php
require_once('admin_guard.php');
require_once('../model/adminModel.php');

function h($s){ return htmlspecialchars($s); }

$msg = "";

if(isset($_GET['approve'])){
  $id = $_GET['approve'];
  if(setShipActive($id, 1)){
    $msg = "Ship approved and activated.";
  }else{
    $msg = "Failed to approve ship.";
  }
}

if(isset($_GET['reject'])){
  $id = $_GET['reject'];
  if(deleteShipById($id)){
    $msg = "Ship rejected and deleted.";
  }else{
    $msg = "Failed to reject ship.";
  }
}

$pendingShips = getPendingShipsAdmin(50);
?>
<!DOCTYPE html>
<html>
<head>
  <title>Pending Approvals</title>
  <link rel="stylesheet" href="../asset/common.css">
  <link rel="stylesheet" href="../asset/components.css">
  <link rel="stylesheet" href="../asset/admin-style.css">
</head>
<body>
  <div class="page">
    <div class="topbar">
      <a class="back-link" href="admin_dashboard.php">Back</a>
      <div style="color: blue; font-weight:bold;">Pending Ship Approvals</div>
      <a href="../controller/admin_logout.php" style="margin-left:auto;" class="back-link">Logout</a>
    </div>

    <?php if($msg != ""){ ?>
      <div class="notice"><?= h($msg) ?></div>
    <?php } ?>

    <div class="card">
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

  </div>
</body>
</html>
