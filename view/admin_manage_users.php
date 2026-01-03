<?php
require_once('admin_guard.php');
require_once('../model/adminModel.php');

function h($s){ return htmlspecialchars($s); }

$msg = "";

if(isset($_GET['delete'])){
  $id = $_GET['delete'];
  if(deleteUserById($id)){
    $msg = "User deleted successfully.";
  }else{
    $msg = "Failed to delete user. (Maybe related rows exist.)";
  }
}

$users = getAllUsersAdmin();
?>
<!DOCTYPE html>
<html>
<head>
  <title>Manage Users</title>
  <link rel="stylesheet" href="../asset/common.css">
  <link rel="stylesheet" href="../asset/components.css">
  <link rel="stylesheet" href="../asset/admin-style.css">
</head>
<body>
  <div class="page">
    <div class="topbar">
      <a class="back-link" href="admin_dashboard.php">Back</a>
      <div style="color: blue; font-weight:bold;">Manage Users</div>
      <a href="../controller/admin_logout.php" style="margin-left:auto;" class="back-link">Logout</a>
    </div>

    <?php if($msg != ""){ ?>
      <div class="notice"><?= h($msg) ?></div>
    <?php } ?>

    <div class="card">
      <div class="table-wrap">
        <table>
          <tr>
            <th>ID</th><th>Name</th><th>Email</th><th>Role</th><th>Balance</th><th>Action</th>
          </tr>

          <?php if(count($users) == 0){ ?>
            <tr><td colspan="6">No users found.</td></tr>
          <?php } else { foreach($users as $u){ ?>
            <tr>
              <td><?= $u['id'] ?></td>
              <td><?= h($u['name']) ?></td>
              <td><?= h($u['email']) ?></td>
              <td><?= h($u['role']) ?></td>
              <td>৳<?= number_format(floatval($u['balance']),2) ?></td>
              <td>
                <a href="admin_manage_users.php?delete=<?= $u['id'] ?>" onclick="return confirm('Delete this user?')">
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
