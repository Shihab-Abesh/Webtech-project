<?php
session_start();
require_once('../model/shipModel.php');

if(!isset($_SESSION['owner_id'])){
    header('location: ../controller/login.php');
    exit();
}

$ownerId = $_SESSION['owner_id'];
$msg = "";

if(isset($_POST['submit'])){
    $name = trim($_POST['ship_name']);
    $capacity = trim($_POST['capacity']);
    $route = trim($_POST['route']);
    $fee = trim($_POST['fee']);
    $active = isset($_POST['active']) ? true : false;

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
            "owner_id" => $ownerId,
            "name" => $name,
            "capacity" => intval($capacity),
            "route" => $route,
            "fee" => floatval($fee),
            "active" => $active
        ];

        if(addShip($ship)){
            $msg = "Ship added successfully!";
        }else{
            $msg = "Failed to add ship. Please try again.";
        }
    }
}
?>
<!DOCTYPE html>
<html>
<head>
  <title>Add Ship</title>
  <link rel="stylesheet" href="../asset/common.css">
  <link rel="stylesheet" href="../asset/components.css">
  <script src="../asset/add_ship.js"></script>
</head>
<body>
  <div class="page">
    <div class="topbar">
      <a class="back-link" href="owner_home.php">
        <img src="../asset/Back34.jpg" alt="Back"> Back
      </a>
      <div style="color: skyblue; font-weight:bold;">Add New Ship</div>
    </div>

    <div class="card">
      <form method="post" onsubmit="return validateShipForm()">
        <div class="filters" style="margin:0 0 10px;">
          <input id="ship_name" type="text" name="ship_name" placeholder="Ship Name">
          <input id="capacity" type="text" name="capacity" placeholder="Capacity">
          <input id="route" type="text" name="route" placeholder="Route">
          <input id="fee" type="text" name="fee" placeholder="Booking Fee (৳)">
        </div>

        <label style="display:block; margin:8px 0 14px;">
        <input type="checkbox" name="active" checked> Active (available for booking)
        </label>

        <button class="btn btn-primary" type="submit" name="submit">Add Ship</button>
        <a class="btn btn-neutral" style="text-decoration:none; margin-left:8px;" href="owner_home.php">Dashboard</a>
        <?php if($msg !== ""){ ?>
          <div class="notice"><?= htmlspecialchars($msg) ?></div>
        <?php } ?>
      </form>
    </div>
  </div>
</body>
</html>
