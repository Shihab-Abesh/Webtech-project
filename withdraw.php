<?php
session_start();

require_once('../model/userModel.php');
require_once('../model/withdrawModel.php');

if(!isset($_SESSION['owner_id'])){
    header('location: ../controller/login.php');
    exit();
}

$ownerId = $_SESSION['owner_id'];
$balance = getBalance($ownerId);
$msg = "";


function mobileOk($m){
  if(strlen($m) != 11) return false;
  if(!ctype_digit($m)) return false;
  if(substr($m, 0, 2) != "01") return false;
  return true;
}

function cardOk($c){
  $len = strlen($c);
  if($len < 13 || $len > 19) return false;
  if(!ctype_digit($c)) return false;
  return true;
}

function h($s){ return htmlspecialchars($s); }

if(isset($_POST['withdraw'])){
  $amount = trim($_POST['amount']);
  $method = isset($_POST['method']) ? $_POST['method'] : "";

  
  $balance = getBalance($ownerId);

  if($amount == ""){
    $msg = "Please enter an amount.";
  }
  else if(!is_numeric($amount) || floatval($amount) <= 0){
    $msg = "Amount must be a number greater than 0.";
  }
  else if(floatval($amount) > $balance){
    $msg = "Amount cannot be greater than available balance.";
  }
  else if($method == ""){
    $msg = "Please select a withdrawal method.";
  }
  else{
    if($method == "bkash"){
      $mobile = trim($_POST['bkash_mobile']);

      if($mobile == ""){
        $msg = "Please enter bKash mobile number.";
      }
      else if(!mobileOk($mobile)){
        $msg = "Warning: Must be 11 digits.";
      }
      else{
        $details = "Mobile: ".$mobile;
        $ok = requestWithdrawal($ownerId, "bKash", $details, floatval($amount));

        if($ok){
          header("Location: withdraw.php");
          exit();
        }else{
          $msg = "Withdraw failed. Please check balance.";
        }
      }
    }

    if($method == "mastercard"){
      $owner = trim($_POST['card_owner']);
      $card  = trim($_POST['card_no']);

      if($owner == ""){
        $msg = "Please enter card owner name.";
      }
      else if($card == ""){
        $msg = "Please enter card number.";
      }
      else if(!cardOk($card)){
        $msg = "Warning: Wrong card number  13 to 19 digits only.";
      }
      else{
        $details = "Owner: ".$owner." | Card: ".$card;
        $ok = requestWithdrawal($ownerId, "MasterCard", $details, floatval($amount));

        if($ok){
          header("Location: withdraw.php");
          exit();
        }else{
          $msg = "Withdraw failed. Please check balance.";
        }
      }
    }
  }
}

$balance = getBalance($ownerId);
$withdrawals = getWithdrawalsByOwner($ownerId, 8);
?>
<!DOCTYPE html>
<html>
<head>
  <title>Withdraw</title>
  <link rel="stylesheet" href="../asset/common.css">
  <link rel="stylesheet" href="../asset/components.css">
  <link rel="stylesheet" href="../asset/tables.css">
  <link rel="stylesheet" href="../asset/withdraw.css">
  <script src="../asset/withdraw.js"></script>
</head>

<body>
  <div class="page">
    <div class="topbar">
      <a class="back-link" href="owner_home.php">
        <img src="../asset/Back34.jpg" alt="Back"> Back
      </a>
      <div style="color:#fff; font-weight:bold;">Withdraw Payment</div>
    </div>

    <div class="card">
      <p><b>Available Balance:</b> ৳<?= number_format($balance,2) ?></p>

      <?php if($msg != ""){ ?>
        <div class="notice"><?= h($msg) ?></div>
      <?php } ?>

      <form method="post" onsubmit="return validateForm()">
        <div class="row">
          <input id="amount" type="text" name="amount" placeholder="Enter withdraw amount">
          <button class="btn btn-primary" type="submit" name="withdraw">Withdraw</button>
          <a class="btn btn-neutral" style="text-decoration:none;" href="owner_home.php">Dashboard</a>
        </div>

        <div class="methodBox">
          <label class="methodItem">
            <input type="radio" name="method" id="bkash" value="bkash" onclick="showFields()">
            <img src="../asset/59.png" alt="bKash">
            <div><b>bKash</b><br><small>Send to mobile</small></div>
          </label>

          <label class="methodItem">
            <input type="radio" name="method" id="mastercard" value="mastercard" onclick="showFields()">
            <img src="../asset/68.png" alt="MasterCard">
            <div><b>MasterCard</b><br><small>Send to card</small></div>
          </label>
        </div>

        <div id="bkashFields" style="display:none;">
          <div class="row">
            <input id="bkash_mobile" type="text" name="bkash_mobile" placeholder="bKash mobile (11 digits)">
          </div>
        </div>

        <div id="mcFields" style="display:none;">
          <div class="row">
            <input id="card_owner" type="text" name="card_owner" placeholder="Card owner name">
            <input id="card_no" type="text" name="card_no" placeholder="Card number (digits only)">
          </div>
        </div>
      </form>

      <h3 style="margin-top:18px;">Recent Withdraw Requests</h3>
      <div class="table-wrap">
        <table>
          <tr><th>#</th><th>Amount</th><th>Method</th><th>Details</th><th>Date & Time</th></tr>

          <?php
          $count = 0;
          foreach($withdrawals as $w){
            $count++;
          ?>
            <tr>
              <td><?= $count ?></td>
              <td>৳<?= number_format($w['amount'],2) ?></td>
              <td><?= h($w['method']) ?></td>
              <td><?= h($w['details']) ?></td>
              <td><?= h($w['created_at']) ?></td>
            </tr>
          <?php } ?>

          <?php if($count == 0){ ?>
            <tr><td colspan="5">No withdraw requests yet.</td></tr>
          <?php } ?>
        </table>
      </div>
    </div>
  </div>
</body>
</html>
