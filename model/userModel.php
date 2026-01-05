<?php
require_once('db.php');

function login($user){
    $con = getConnection();

    $email    = $user['email'];
    $password = $user['password']; 

    $stmt = $con->prepare("SELECT * FROM users WHERE email = ? LIMIT 1");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if($result && $result->num_rows == 1){
        $row = $result->fetch_assoc();

        
        if(password_verify($password, $row['password'])){
            return $row;
        }
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
  
if(emailExists($email)){
        return "email_exists";
    }

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

function emailExists($email){
    $con = getConnection();
    $stmt = $con->prepare("SELECT id FROM users WHERE email=? LIMIT 1");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $res = $stmt->get_result();
    return ($res && $res->num_rows == 1);
}

?>
