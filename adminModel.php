<?php
require_once('db.php');


function adminLogin($admin){
    $con = getConnection();

    $adminId = $admin['admin_id'];
    $password = $admin['password'];

    $sql = "select * from admin where admin_id='{$adminId}' and password='{$password}'";
    $result = mysqli_query($con, $sql);

    if($result && mysqli_num_rows($result) == 1){
        return true;
    }else{
        return false;
    }
}

function countUsers(){
    $con = getConnection();
    $sql = "select count(*) as total from users";
    $result = mysqli_query($con, $sql);
    $row = $result ? mysqli_fetch_assoc($result) : null;
    return $row ? intval($row['total']) : 0;
}

function countOwners(){
    $con = getConnection();
    $sql = "select count(*) as total from users where role='ship owner' OR role='owner' OR role='shipowner'";
    $result = mysqli_query($con, $sql);
    $row = $result ? mysqli_fetch_assoc($result) : null;
    return $row ? intval($row['total']) : 0;
}

function countCustomers(){
    $con = getConnection();
    $sql = "select count(*) as total from users where role='customer'";
    $result = mysqli_query($con, $sql);
    $row = $result ? mysqli_fetch_assoc($result) : null;
    return $row ? intval($row['total']) : 0;
}

function countShips(){
    $con = getConnection();
    $sql = "select count(*) as total from ships";
    $result = mysqli_query($con, $sql);
    $row = $result ? mysqli_fetch_assoc($result) : null;
    return $row ? intval($row['total']) : 0;
}

function countPendingShips(){
    $con = getConnection();
    $sql = "select count(*) as total from ships where active=0";
    $result = mysqli_query($con, $sql);
    $row = $result ? mysqli_fetch_assoc($result) : null;
    return $row ? intval($row['total']) : 0;
}

function getPendingShipsAdmin($limit=8){
    $con = getConnection();
    $limit = intval($limit);
    $sql = "select s.*, u.name as owner_name, u.email as owner_email
            from ships s
            join users u on u.id = s.owner_id
            where s.active=0
            order by s.id desc
            limit {$limit}";
    $result = mysqli_query($con, $sql);
    $rows = [];
    if($result){
        while($r = mysqli_fetch_assoc($result)){
            $rows[] = $r;
        }
    }
    return $rows;
}

function setShipActive($shipId, $to){
    $con = getConnection();
    $shipId = intval($shipId);
    $to = intval($to) ? 1 : 0;

    $sql = "update ships set active={$to} where id={$shipId}";
    if(mysqli_query($con, $sql)){
        return true;
    }
    return false;
}

function deleteShipById($shipId){
    $con = getConnection();
    $shipId = intval($shipId);

    
    mysqli_query($con, "delete from bookings where ship_id={$shipId}");
    $sql = "delete from ships where id={$shipId}";
    if(mysqli_query($con, $sql)){
        return true;
    }
    return false;
}

function getAllShipsAdmin(){
    $con = getConnection();
    $sql = "select s.*, u.name as owner_name, u.email as owner_email
            from ships s
            join users u on u.id = s.owner_id
            order by s.id desc";
    $result = mysqli_query($con, $sql);
    $rows = [];
    if($result){
        while($r = mysqli_fetch_assoc($result)){
            $rows[] = $r;
        }
    }
    return $rows;
}

function getAllUsersAdmin(){
    $con = getConnection();
    $sql = "select id, name, email, role, balance from users order by id desc";
    $result = mysqli_query($con, $sql);
    $rows = [];
    if($result){
        while($r = mysqli_fetch_assoc($result)){
            $rows[] = $r;
        }
    }
    return $rows;
}

function deleteUserById($id){
    $con = getConnection();
    $id = intval($id);

   
    mysqli_query($con, "delete from bookings where customer_id={$id}");

    
    $shipIds = [];
    $res = mysqli_query($con, "select id from ships where owner_id={$id}");
    if($res){
        while($r = mysqli_fetch_assoc($res)){
            $shipIds[] = intval($r['id']);
        }
    }
    if(count($shipIds) > 0){
        $in = implode(',', $shipIds);
        mysqli_query($con, "delete from bookings where ship_id in ({$in})");
    }
    mysqli_query($con, "delete from ships where owner_id={$id}");
    mysqli_query($con, "delete from withdrawals where owner_id={$id}");

    $sql = "delete from users where id={$id}";
    if(mysqli_query($con, $sql)){
        return true;
    }
    return false;
}

function countPendingBookings(){
    $con = getConnection();
    $sql = "select count(*) as total from bookings where status='Pending'";
    $result = mysqli_query($con, $sql);
    $row = $result ? mysqli_fetch_assoc($result) : null;
    return $row ? intval($row['total']) : 0;
}

function getPendingBookingsAdmin($limit=8){
    $con = getConnection();
    $limit = intval($limit);

  
    $sql = "select b.*, s.name as ship_name, u.name as customer_name, u.email as customer_email
            from bookings b
            join ships s on s.id = b.ship_id
            join users u on u.id = b.customer_id
            where b.status='Pending'
            order by b.id desc
            limit {$limit}";
    $result = mysqli_query($con, $sql);

    $rows = [];
    if($result){
        while($r = mysqli_fetch_assoc($result)){
            $rows[] = $r;
        }
    }
    return $rows;
}
?>