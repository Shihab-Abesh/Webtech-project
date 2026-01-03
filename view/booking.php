<?php
session_start();
require_once('../model/shipModel.php');

if(!isset($_SESSION['customer_id'])){
    header('location: ../controller/login.php');
    exit();
}

$ships = getActiveShips();
function h($s){ return htmlspecialchars($s); }
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Available Shipments</title>
  <link rel="stylesheet" href="../asset/book-style.css">
</head>
<body>

<div class="page">
  <div class="top-bar">
    <a href="cdashboard.php" class="back-btn">← Back</a>
  </div>

  <h2 class="title">Available Shipments</h2>

  <div class="table-container">
    <table>
      <thead>
        <tr>
          <th>Ship Name</th>
          <th>Capacity</th>
          <th>Route</th>
          <th>Fee</th>
          <th>Status</th>
          <th>Action</th>
        </tr>
      </thead>

      <tbody>
        <?php foreach($ships as $s){ ?>
        <tr>
          <td><?= h($s['name']) ?></td>
          <td><?= intval($s['capacity']) ?></td>
          <td><?= h($s['route']) ?></td>
          <td><?= number_format($s['fee'],2) ?>TK</td>
          <td><span class="badge available">Available</span></td>
          <td><a href="book_ship.php?ship_id=<?= $s['id'] ?>" class="book-btn">Book</a></td>
        </tr>
        <?php } ?>

        <?php if(count($ships) == 0){ ?>
        <tr><td colspan="6">No active ships available right now.</td></tr>
        <?php } ?>
      </tbody>
    </table>
  </div>
</div>

</body>
</html>
