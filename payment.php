<?php
require_once 'includes/config.php'; require_once 'includes/functions.php'; require_tourist();
$id=(int)($_GET['booking_id']??0);
$s=$pdo->prepare('SELECT booking_id FROM bookings WHERE booking_id=? AND tourist_id=?');$s->execute([$id,$_SESSION['tourist_id']]);
if(!$s->fetchColumn()){flash('Booking not found.','error');header('Location: bookings.php');exit;}
header('Location: booking_receipt.php?booking_id='.$id);exit;
?>