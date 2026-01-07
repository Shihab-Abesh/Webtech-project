<?php
session_start();

require_once('../model/bookingModel.php');
if(!isset($_SESSION['owner_id'])){
    header('Content-Type: application/json');
    echo json_encode(['ok'=>false, 'items'=>[], 'msg'=>'not logged in']);
    exit();
}
$ownerId = $_SESSION['owner_id'];
$q = $_POST['q'] ?? '';
$status = $_POST['status'] ?? '';

setcookie('owner_booking_q', $q, time()+10, '/');
setcookie('owner_booking_status', $status, time()+10, '/');
$items = searchBookingsByOwner($ownerId, $q, $status);

header('Content-Type: application/json');
echo json_encode(['ok'=>true, 'items'=>$items]);
?>
