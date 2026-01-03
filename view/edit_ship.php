<?php
session_start();
require_once('../model/shipModel.php');

if(!isset($_SESSION['owner_id'])){
    header('location: ../controller/login.php');
    exit();
}

$ownerId = $_SESSION['owner_id'];
$msg = "";

if(isset($_POST['update'])){
    $id = intval($_POST['id']);

    $name = trim($_POST['ship_name']);
    $capacity = trim($_POST['capacity']);
    $route = trim($_POST['route']);
    $fee = trim($_POST['fee']);
    $active = ($_POST['active'] === "1") ? true : false;

    if($name === "" || $capacity === "" || $route === "" || $fee === ""){
        $msg = "Please fill in all fields.";
    }
    else if(!is_numeric($capacity) || intval($capacity) <= 0){
        $msg = "Capacity must be a number greater than 0.";
    }
    else if(!is_numeric($fee) || floatval($fee) <= 0){
        $msg = "Fee must be a number greater than 0.";
    }
    else{
        $ship = [
            "id" => $id,
            "owner_id" => $ownerId,
            "name" => $name,
            "capacity" => intval($capacity),
            "route" => $route,
            "fee" => floatval($fee),
            "active" => $active
        ];

        if(updateShip($ship)){
            $msg = "Ship updated successfully!";
        }else{
            $msg = "Failed to update ship (or ship not found).";
        }
    }
}

$ships = getAllShipsByOwner($ownerId);
?>
<!DOCTYPE html>
<html>
<head>
  <title>Edit Ship</title>
  <link rel="stylesheet" href="../asset/common.css">
  <link rel="stylesheet" href="../asset/components.css">
  <link rel="stylesheet" href="../asset/tables.css">
  <script src="../asset/edit_ship.js"></script>
</head>
<body>
  <div class="page">
    <div class="topbar">
      <a class="back-link" href="owner_home.php">
        <img src="../asset/Back34.jpg" alt="Back"> Back
      </a>
      <div style="color:#fff; font-weight:bold;">Update Ship Details</div>
    </div>

    <div class="card">
      <select id="onlyActive" onchange="filterShips()">
        <option value="all">All</option>
        <option value="active">Active only</option>
        <option value="inactive">Inactive only</option>
      </select>
      <a class="btn btn-neutral btn-small" style="text-decoration:none;" href="owner_home.php">Dashboard</a>
    </div>

    <?php if($msg !== ""){ ?>
      <div class="notice"><?= htmlspecialchars($msg) ?></div>
    <?php } ?>

   <div class="table-wrap">
    <table id="shipsTable">
    <thead>
          <tr>
            <th>Name</th>
            <th>Capacity</th>
            <th>Route</th>
            <th>Fee (৳)</th>
            <th>Availability</th>
            <th>Created</th>
            <th>Updated</th>
            <th>Action</th>
       </tr>
     </thead>
        <tbody>
        <?php foreach($ships as $ship){
            $isActive = intval($ship['active']) === 1 ? true : false;
            $created = isset($ship['created_at']) ? $ship['created_at'] : "";
            $updated = isset($ship['updated_at']) ? $ship['updated_at'] : "";
            $feeVal = isset($ship['fee']) ? $ship['fee'] : "";
        ?>
          <tr data-active="<?= $isActive ? "1" : "0" ?>">
            <form method="post">
              <td><input name="ship_name" value="<?= htmlspecialchars($ship['name']) ?>"></td>
              <td><input name="capacity" value="<?= htmlspecialchars($ship['capacity']) ?>"></td>
              <td><input name="route" value="<?= htmlspecialchars($ship['route']) ?>"></td>
              <td><input name="fee" value="<?= htmlspecialchars($feeVal) ?>"></td>

              <td>
                <select name="active">
                  <option value="1" <?= $isActive ? "selected" : "" ?>>Active</option>
                  <option value="0" <?= !$isActive ? "selected" : "" ?>>Inactive</option>
                </select>
              </td>

              <td><?= htmlspecialchars($created) ?></td>
              <td><?= htmlspecialchars($updated) ?></td>
              <td>
                <input type="hidden" name="id" value="<?= intval($ship['id']) ?>">
                <button class="btn btn-primary btn-small" type="submit" name="update">Update</button>
              </td>
            </form>
          </tr>
        <?php } ?>

        <?php if(count($ships) === 0){ ?>
          <tr><td colspan="8">No ships added yet.</td></tr>
        <?php } ?>

        </tbody>
      </table>
    </div>
  </div>
</body>
</html>
