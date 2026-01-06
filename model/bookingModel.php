<?php
require_once('db.php');
function addBooking($b){
    $con = getConnection();

    $shipId = intval($b['ship_id']);
    $customerId = intval($b['customer_id']);
    $customerName = esc($con, $b['customer_name']);
    $destination = esc($con, $b['destination']);
    $cargoType = esc($con, $b['cargo_type']);
    $amount = floatval($b['amount']);

    $sql = "INSERT INTO bookings (ship_id, customer_id, customer_name, destination, cargo_type, status, amount, requested_at)
            VALUES ({$shipId}, {$customerId}, '{$customerName}', '{$destination}', '{$cargoType}', 'Pending', {$amount}, NOW())";

    return mysqli_query($con, $sql) ? true : false;
}

function getBookingsByCustomer($customerId){
    $con = getConnection();
    $customerId = intval($customerId);

    $sql = "SELECT b.*, s.name AS ship_name, s.route, s.fee
            FROM bookings b
            JOIN ships s ON s.id=b.ship_id
            WHERE b.customer_id={$customerId}
            ORDER BY b.id DESC";
    $result = mysqli_query($con, $sql);

    $items = [];
    if($result){
        while($row = mysqli_fetch_assoc($result)){
            $items[] = $row;
        }
    }
    return $items;
}

function getRecentBookingsByCustomer($customerId, $limit = 5){
    $con = getConnection();
    $customerId = intval($customerId);
    $limit = intval($limit);

    $sql = "SELECT b.*, s.route
            FROM bookings b
            JOIN ships s ON s.id=b.ship_id
            WHERE b.customer_id={$customerId}
            ORDER BY b.id DESC
            LIMIT {$limit}";
    $result = mysqli_query($con, $sql);

    $items = [];
    if($result){
        while($row = mysqli_fetch_assoc($result)){
            $items[] = $row;
        }
    }
    return $items;
}

function getPendingBookingsByOwner($ownerId){
    $con = getConnection();
    $ownerId = intval($ownerId);

    $sql = "SELECT b.*, s.name AS ship_name
            FROM bookings b
            JOIN ships s ON s.id=b.ship_id
            WHERE s.owner_id={$ownerId} AND b.status='Pending'
            ORDER BY b.id DESC";
    $result = mysqli_query($con, $sql);

    $items = [];
    if($result){
        while($row = mysqli_fetch_assoc($result)){
            $items[] = $row;
        }
    }
    return $items;
}

function getHistoryBookingsByOwner($ownerId){
    $con = getConnection();
    $ownerId = intval($ownerId);

    $sql = "SELECT b.*, s.name AS ship_name
            FROM bookings b
            JOIN ships s ON s.id=b.ship_id
            WHERE s.owner_id={$ownerId}
            ORDER BY b.id DESC";
    $result = mysqli_query($con, $sql);

    $items = [];
    if($result){
        while($row = mysqli_fetch_assoc($result)){
            $items[] = $row;
        }
    }
    return $items;
}

function countBookingsByOwnerAndStatus($ownerId, $status){
    $con = getConnection();
    $ownerId = intval($ownerId);
    $status = esc($con, $status);

    $sql = "SELECT COUNT(*) AS c
            FROM bookings b
            JOIN ships s ON s.id=b.ship_id
            WHERE s.owner_id={$ownerId} AND b.status='{$status}'";
    $r = mysqli_query($con, $sql);
    if($r){
        $row = mysqli_fetch_assoc($r);
        return intval($row['c']);
    }
    return 0;
}


function updateBookingStatus($bookingId, $ownerId, $newStatus){
    $con = getConnection();
    $bookingId = intval($bookingId);
    $ownerId = intval($ownerId);

    if($newStatus !== "Accepted" && $newStatus !== "Rejected"){
        return false;
    }

   
    $sql1 = "SELECT b.id, b.status, b.amount, s.owner_id
             FROM bookings b
             JOIN ships s ON s.id=b.ship_id
             WHERE b.id={$bookingId} LIMIT 1";
    $r = mysqli_query($con, $sql1);
    if(!$r || mysqli_num_rows($r) != 1){
        return false;
    }

    $row = mysqli_fetch_assoc($r);
    if(intval($row['owner_id']) != $ownerId){
        return false;
    }

    $oldStatus = $row['status'];
    $amount = floatval($row['amount']);

    
    $sql2 = "UPDATE bookings SET status='{$newStatus}', updated_at=NOW() WHERE id={$bookingId}";
    if(!mysqli_query($con, $sql2)){
        return false;
    }

    
    if($oldStatus !== "Accepted" && $newStatus === "Accepted"){
        $sql3 = "UPDATE users SET balance = balance + {$amount} WHERE id={$ownerId}";
        if(!mysqli_query($con, $sql3)){
            return false;
        }
    }

    return true;
}
?>
