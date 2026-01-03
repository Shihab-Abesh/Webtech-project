<?php
require_once('db.php');

function addShip($ship){
    $con = getConnection();

    $ownerId  = intval($ship['owner_id']);
    $name     = esc($con, $ship['name']);
    $capacity = intval($ship['capacity']);
    $route    = esc($con, $ship['route']);
    $fee      = floatval($ship['fee']);
    $active   = intval($ship['active']); 

    $sql = "insert into ships (owner_id, name, capacity, route, fee, active, created_at)
            values({$ownerId}, '{$name}', {$capacity}, '{$route}', {$fee}, {$active}, NOW())";

    if(mysqli_query($con, $sql)){
        return true;
    }else{
        return false;
    }
}

function getAllShipsByOwner($ownerId){
    $con = getConnection();
    $ownerId = intval($ownerId);

    $sql = "select * from ships where owner_id={$ownerId} order by id desc";
    $result = mysqli_query($con, $sql);

    $ships = [];
    if($result){
        while($row = mysqli_fetch_assoc($result)){
            $ships[] = $row;
        }
    }
    return $ships;
}

function getShipById($shipId, $ownerId){
    $con = getConnection();
    $shipId = intval($shipId);
    $ownerId = intval($ownerId);

    $sql = "select * from ships where id={$shipId} and owner_id={$ownerId} limit 1";
    $result = mysqli_query($con, $sql);

    if($result && mysqli_num_rows($result) == 1){
        return mysqli_fetch_assoc($result);
    }else{
        return false;
    }
}

function updateShip($ship){
    $con = getConnection();

    $id       = intval($ship['id']);
    $ownerId  = intval($ship['owner_id']);
    $name     = esc($con, $ship['name']);
    $capacity = intval($ship['capacity']);
    $route    = esc($con, $ship['route']);
    $fee      = floatval($ship['fee']);
    $active   = intval($ship['active']); // 1 or 0

    $sql = "update ships set
              name='{$name}',
              capacity={$capacity},
              route='{$route}',
              fee={$fee},
              active={$active},
              updated_at=NOW()
            where id={$id} and owner_id={$ownerId}";

    if(mysqli_query($con, $sql)){
        return true;
    }else{
        return false;
    }
}

function deleteShip($id, $ownerId){
    $con = getConnection();
    $id = intval($id);
    $ownerId = intval($ownerId);

    $sql = "delete from ships where id={$id} and owner_id={$ownerId}";
    return mysqli_query($con, $sql) ? true : false;
}

function countShipsByOwner($ownerId){
    $con = getConnection();
    $ownerId = intval($ownerId);

    $r = mysqli_query($con, "select count(*) as c from ships where owner_id={$ownerId}");
    if($r){
        $row = mysqli_fetch_assoc($r);
        return intval($row['c']);
    }
    return 0;
}

function countActiveShipsByOwner($ownerId){
    $con = getConnection();
    $ownerId = intval($ownerId);

    $r = mysqli_query($con, "select count(*) as c from ships where owner_id={$ownerId} and active=1");
    if($r){
        $row = mysqli_fetch_assoc($r);
        return intval($row['c']);
    }
    return 0;
}

function getActiveShips(){
    $con = getConnection();
    $sql = "select s.*, u.name as owner_name
            from ships s
            join users u on u.id = s.owner_id
            where s.active=1
            order by s.id desc";
    $result = mysqli_query($con, $sql);

    $ships = [];
    if($result){
        while($row = mysqli_fetch_assoc($result)){
            $ships[] = $row;
        }
    }
    return $ships;
}

function getActiveShipById($shipId){
    $con = getConnection();
    $shipId = intval($shipId);

    $sql = "select s.*, u.name as owner_name
            from ships s
            join users u on u.id = s.owner_id
            where s.id={$shipId} and s.active=1
            limit 1";
    $result = mysqli_query($con, $sql);

    if($result && mysqli_num_rows($result) == 1){
        return mysqli_fetch_assoc($result);
    }
    return false;
}
?>
