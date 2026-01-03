<?php
require_once('db.php');

function login($user){
    $con = getConnection();

    $email = esc($con, $user['email']);
    $password = esc($con, $user['password']);

    $sql = "select * from users where email='{$email}' and password='{$password}' limit 1";
    $result = mysqli_query($con, $sql);

    if($result && mysqli_num_rows($result) == 1){
        return mysqli_fetch_assoc($result);
    }else{
        return false;
    }
}


function addUser($user){
    $con = getConnection();

    $role = esc($con, $user['role']);
    $name = esc($con, $user['name']);
    $email = esc($con, $user['email']);
    $password = esc($con, $user['password']);
    $phone = esc($con, $user['phone']);
    $dob = esc($con, $user['dob']);

    $sql = "insert into users (role, name, email, password, phone, dob, balance, created_at)
            values('{$role}', '{$name}', '{$email}', '{$password}', '{$phone}', '{$dob}', 0.00, NOW())";

    if(mysqli_query($con, $sql)){
        return true;
    }else{
        return false;
    }
}

function getUserById($id){
    $con = getConnection();
    $id = intval($id);

    $sql = "select * from users where id='{$id}'";
    $result = mysqli_query($con, $sql);

    if($result && mysqli_num_rows($result) == 1){
        return mysqli_fetch_assoc($result);
    }else{
        return false;
    }
}

function getBalance($userId){
    $con = getConnection();
    $userId = intval($userId);

    $sql = "select balance from users where id={$userId} limit 1";
    $result = mysqli_query($con, $sql);

    if($result && mysqli_num_rows($result) == 1){
        $row = mysqli_fetch_assoc($result);
        return floatval($row['balance']);
    }
    return 0;
}

function updateBalance($userId, $newBalance){
    $con = getConnection();
    $userId = intval($userId);
    $newBalance = floatval($newBalance);

    $sql = "update users set balance={$newBalance} where id={$userId}";
    if(mysqli_query($con, $sql)){
        return true;
    }else{
        return false;
    }
}
?>