<?php
session_start();
require_once('../model/bookingModel.php');

if(!isset($_SESSION['owner_id'])){
    header('location: ../controller/login.php');
    exit();
}

$ownerId = $_SESSION['owner_id'];
$items = getHistoryBookingsByOwner($ownerId);

header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename=booking_history.csv');

$output = fopen('php://output', 'w');

fputcsv($output, ['Booking ID', 'Ship', 'Customer', 'Destination', 'Cargo', 'Status', 'Amount', 'Requested At', 'Updated At']);

foreach($items as $b){
    fputcsv($output, [
        $b['id'],
        $b['ship_name'],
        $b['customer_name'],
        $b['destination'],
        $b['cargo_type'],
        $b['status'],
        $b['amount'],
        $b['requested_at'],
        $b['updated_at']
    ]);
}

fclose($output);
exit();
?>
