<?php
require_once('admin_guard.php');
require_once('../model/adminModel.php');

function h($s){ return htmlspecialchars($s); }

$msg = "";

if(isset($_GET['toggle'])){
  $id = $_GET['toggle'];
  $to = isset($_GET['to']) ? $_GET['to'] : 0;

  if(setShipActive($id, $to)){
    $msg = "Ship status updated.";
  }else{
    $msg = "Failed to update status.";
  }
}

if(isset($_GET['delete'])){
  $id = $_GET['delete'];
  if(deleteShipById($id)){
    $msg = "Ship deleted.";
  }else{
    $msg = "Failed to delete ship.";
  }
}

$ships = getAllShipsAdmin();
?>
<!DOCTYPE html>
<html>
<head>
  <title>Manage Shipments</title>
  <link rel="stylesheet" href="../asset/common.css">
  <link rel="stylesheet" href="../asset/components.css">
  <link rel="stylesheet" href="../asset/admin-style.css">
</head>
<body>
  <div class="page">
    <div class="topbar">
      <a class="back-link" href="admin_dashboard.php">Back</a>
      <div style="color: blue; font-weight:bold;">Manage Shipments</div>
      <a href="../controller/admin_logout.php" style="margin-left:auto;" class="back-link">Logout</a>
    </div>

    <?php if($msg != ""){ ?>
      <div class="notice"><?= h($msg) ?></div>
    <?php } ?>

    <div class="card">
      <div class="table-wrap">
        <table>
          <tr>
            <th>ID</th><th>Owner</th><th>Ship</th><th>Capacity</th><th>Route</th><th>Fee</th><th>Active</th><th>Action</th>
          </tr>

          <?php if(count($ships) == 0){ ?>
            <tr><td colspan="8">No ships found.</td></tr>
          <?php } else { foreach($ships as $s){ ?>
            <tr>
              <td><?= $s['id'] ?></td>
              <td><?= h($s['owner_name']) ?> (<?= h($s['owner_email']) ?>)</td>
              <td><?= h($s['name']) ?></td>
              <td><?= $s['capacity'] ?></td>
              <td><?= h($s['route']) ?></td>
              <td>৳<?= number_format($s['fee'],2) ?></td>
              <td><?= $s['active'] ? 'Yes' : 'No' ?></td>
              <td>
                <?php if($s['active']){ ?>
                  <a href="admin_manage_shipments.php?toggle=<?= $s['id'] ?>&to=0" onclick="return confirm('Deactivate this ship?')">
                    <button class="btn-small btn-toggle">Deactivate</button>
                  </a>
                <?php } else { ?>
                  <a href="admin_manage_shipments.php?toggle=<?= $s['id'] ?>&to=1" onclick="return confirm('Activate this ship?')">
                    <button class="btn-small btn-approve">Activate</button>
                  </a>
                <?php } ?>

                <a href="admin_manage_shipments.php?delete=<?= $s['id'] ?>" onclick="return confirm('Delete this ship?')">
                  <button class="btn-small btn-delete">Delete</button>
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
