<?php
require_once('db.php');

function getWithdrawalsByOwner($ownerId, $limit = 0){
    $con = getConnection();
    $ownerId = intval($ownerId);

    $sql = "SELECT * FROM withdrawals WHERE owner_id={$ownerId} ORDER BY id DESC";
    if($limit > 0){
        $sql .= " LIMIT ".intval($limit);
    }

    $result = mysqli_query($con, $sql);

    $items = [];
    if($result){
        while($row = mysqli_fetch_assoc($result)){
            $items[] = $row;
        }
    }
    return $items;
}

function countWithdrawalsByOwner($ownerId){
    $con = getConnection();
    $ownerId = intval($ownerId);

    $r = mysqli_query($con, "SELECT COUNT(*) AS c FROM withdrawals WHERE owner_id={$ownerId}");
    if($r){
        $row = mysqli_fetch_assoc($r);
        return intval($row['c']);
    }
    return 0;
}

function requestWithdrawal($ownerId, $method, $details, $amount){
    $con = getConnection();

    $ownerId = intval($ownerId);
    $method = esc($con, $method);
    $details = esc($con, $details);
    $amount = floatval($amount);

    
    $r = mysqli_query($con, "SELECT balance FROM users WHERE id={$ownerId} LIMIT 1");
    if(!$r || mysqli_num_rows($r) != 1){
        return false;
    }
    $row = mysqli_fetch_assoc($r);
    $balance = floatval($row['balance']);
    if($amount > $balance){
        return false;
    }

   
    $sql = "INSERT INTO withdrawals (owner_id, method, details, amount, created_at)
            VALUES ({$ownerId}, '{$method}', '{$details}', {$amount}, NOW())";
    if(!mysqli_query($con, $sql)){
        return false;
    }

    
    $sql2 = "UPDATE users SET balance = balance - {$amount} WHERE id={$ownerId}";
    if(!mysqli_query($con, $sql2)){
        return false;
    }

    return true;
}
?>
