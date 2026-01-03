<?php
session_start();
require_once('../model/shipModel.php');
require_once('../model/bookingModel.php');

if(!isset($_SESSION['customer_id'])){
    header('location: ../controller/login.php');
    exit();
}

$customerId = $_SESSION['customer_id'];
$customerName = isset($_SESSION['customer_name']) ? $_SESSION['customer_name'] : "Customer";

$shipId = isset($_GET['ship_id']) ? intval($_GET['ship_id']) : 0;
$ship = getActiveShipById($shipId);

if(!$ship){
    header('location: booking.php');
    exit();
}

$msg = "";

if(isset($_POST['book'])){
    $destination = trim($_POST['destination']);
    $cargo = trim($_POST['cargo_type']);

    if($destination == "" || $cargo == ""){
        $msg = "Please fill in all fields.";
    }else{
        $booking = [
            "ship_id" => $shipId,
            "customer_id" => $customerId,
            "customer_name" => $customerName,
            "destination" => $destination,
            "cargo_type" => $cargo,
            "amount" => $ship['fee']
        ];

        if(addBooking($booking)){
            header('location: shipment.php');
            exit();
        }else{
            $msg = "Booking failed. Try again.";
        }
    }
}

function h($s){ return htmlspecialchars($s); }
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Book Ship</title>
  <link rel="stylesheet" href="../asset/book-style.css">
  
</head>
<body>

<div class="page">
  <div class="top-bar">
    <a href="booking.php" class="back-btn">← Back</a>
  </div>

  <h2 class="title">Book Ship</h2>

  <div class="form-box">
    <p><b>Ship:</b> <?= h($ship['name']) ?></p>
    <p><b>Route:</b> <?= h($ship['route']) ?></p>
    <p><b>Fee:</b> <?= number_format($ship['fee'],2) ?>TK</p>

    <?php if($msg != ""){ ?>
      <div style="padding:10px;border-radius:10px;background:#fff3cd;margin:10px 0;"><?= h($msg) ?></div>
    <?php } ?>

    <form method="post">
      <div class="form-row">
        <input type="text" name="destination" placeholder="Destination" value="">
        <input type="text" name="cargo_type" placeholder="Cargo Type" value="">
      </div>
      <button class="btn2" type="submit" name="book">Confirm Booking</button>
    </form>
  </div>
</div>

</body>
</html>
